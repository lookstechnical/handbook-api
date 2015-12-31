<?php
/**
 * Created by PhpStorm.
 * User: richardellis
 * Date: 04/11/15
 * Time: 23:35
 */


namespace App\Http\Controllers;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Hash;
use Config;
use Validator;
use Illuminate\Http\Request;
use GuzzleHttp;
use GuzzleHttp\Subscriber\Oauth\Oauth1;
use App\User;

class AuthController extends Controller
{
    protected function createToken($user)
    {
        $customClaims = [
            'sub' => $user->_id,
            'iat' => time(),
            'exp' => time() + (2 * 7 * 24 * 60 * 60)
        ];
        $token = JWTAuth::fromUser($user);
       // $payload = app('tymon.jwt.payload.factory')->make($customClaims);

        return $token;//JWTAuth::encode($payload, Config::get('app.token_secret'));
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()], 400);
        }

        $user = new User();
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->birthday = $request->input('birthday');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->save();



        return response()->json($user,200);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            // verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        // if no errors are encountered we can return a JWT
        return response()->json(compact('token'));
    }

    public function facebook(Request $request)
    {
        $accessTokenUrl = 'https://graph.facebook.com/v2.3/oauth/access_token';
        $graphApiUrl = 'https://graph.facebook.com/v2.3/me';
        $params = [
            'code' => $request->input('code'),
            'client_id' => $request->input('clientId'),
            'redirect_uri' => $request->input('redirectUri'),
            'client_secret' => Config::get('app.facebook_secret')
        ];
        $client = new GuzzleHttp\Client();
        // Step 1. Exchange authorization code for access token.
        $accessToken = json_decode($client->get($accessTokenUrl, ['query' => $params])->getBody(),true);
        $accessToken['fields'] = "id,name,email,first_name,last_name,birthday";
        // Step 2. Retrieve profile information about the current user.
        $profile = json_decode($client->get($graphApiUrl, ['query' => $accessToken])->getBody(),true);


        // Step 3a. If user is already signed in then link accounts.
        if ($request->header('Authorization'))
        {

           /* $user = User::where('facebook', '=', $profile['id']);
            if ($user->first())
            {
                return response()->json(['message' => 'There is already a Facebook account that belongs to you'], 409);
            }*/
            $user = JWTAuth::parseToken()->authenticate();

            $user->facebook = $profile['id'];
            $user->displayName = $user->displayName || $profile['name'];
            $user->save();
            return response()->json(['token' => $this->createToken($user)]);
        }
        // Step 3b. Create a new user account or return an existing one.
        else
        {
            $user = User::where('facebook', '=', $profile['id']);
            if ($user->first())
            {
                return response()->json(['token' => $this->createToken($user->first())]);
            }
            $user = new User;
            $user->facebook = $profile['id'];
            $user->displayName = $profile['name'];
            $user->email = $profile['email'];
            $user->first_name = $profile['first_name'];
            $user->last_name = $profile['last_name'];
            $user->save();
            return response()->json(['token' => $this->createToken($user)]);
        }
    }


    public function twitter(Request $request)
    {
        $requestTokenUrl = 'https://api.twitter.com/oauth/request_token';
        $accessTokenUrl = 'https://api.twitter.com/oauth/access_token';
        $profileUrl = 'https://api.twitter.com/1.1/users/show.json?screen_name=';
        $client = new GuzzleHttp\Client();
        // Part 1 of 2: Initial request from Satellizer.
        if (!$request->input('oauth_token') || !$request->input('oauth_verifier'))
        {
            $requestTokenOauth = new Oauth1([
                'consumer_key' => Config::get('app.twitter_key'),
                'consumer_secret' => Config::get('app.twitter_secret'),
                'callback' => Config::get('app.twitter_callback')
            ]);
            $client->getEmitter()->attach($requestTokenOauth);
            // Step 1. Obtain request token for the authorization popup.
            $requestTokenResponse = $client->post($requestTokenUrl, ['auth' => 'oauth']);
            $oauthToken = array();
            parse_str($requestTokenResponse->getBody(), $oauthToken);
            // Step 2. Send OAuth token back to open the authorization screen.
            return response()->json($oauthToken);
        }
        // Part 2 of 2: Second request after Authorize app is clicked.
        else
        {
            $accessTokenOauth = new Oauth1([
                'consumer_key' => Config::get('app.twitter_key'),
                'consumer_secret' => Config::get('app.twitter_secret'),
                'token' => $request->input('oauth_token'),
                'verifier' => $request->input('oauth_verifier')
            ]);
            $client->getEmitter()->attach($accessTokenOauth);
            // Step 3. Exchange oauth token and oauth verifier for access token.
            $accessTokenResponse = $client->post($accessTokenUrl, ['auth' => 'oauth'])->getBody();
            $accessToken = array();
            parse_str($accessTokenResponse, $accessToken);
            $profileOauth = new Oauth1([
                'consumer_key' => Config::get('app.twitter_key'),
                'consumer_secret' => Config::get('app.twitter_secret'),
                'oauth_token' => $accessToken['oauth_token']
            ]);
            $client->getEmitter()->attach($profileOauth);
            // Step 4. Retrieve profile information about the current user.
            $profile = $client->get($profileUrl . $accessToken['screen_name'], ['auth' => 'oauth'])->json();
            // Step 5a. Link user accounts.
            if ($request->header('Authorization'))
            {
                $user = User::where('twitter', '=', $profile['id']);
                if ($user->first())
                {
                    return response()->json(['message' => 'There is already a Twitter account that belongs to you'], 409);
                }
                $token = explode(' ', $request->header('Authorization'))[1];
                $payload = (array) JWT::decode($token, Config::get('app.token_secret'), array('HS256'));
                $user = User::find($payload['sub']);
                $user->twitter = $profile['id'];
                $user->displayName = $user->displayName || $profile['screen_name'];
                $user->save();
                return response()->json(['token' => $this->createToken($user)]);
            }
            // Step 5b. Create a new user account or return an existing one.
            else
            {
                $user = User::where('twitter', '=', $profile['id']);
                if ($user->first())
                {
                    return response()->json(['token' => $this->createToken($user->first())]);
                }
                $user = new User;
                $user->twitter = $profile['id'];
                $user->displayName = $profile['screen_name'];
                $user->save();
                return response()->json(['token' => $this->createToken($user)]);
            }
        }
    }

}


