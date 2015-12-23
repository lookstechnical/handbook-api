<?php
/**
 * Created by PhpStorm.
 * User: richardellis
 * Date: 04/11/15
 * Time: 23:35
 */


namespace App\Http\Controllers;

use JWTAuth;
use Hash;
use Config;
use Validator;
use Input;
use Illuminate\Http\Request;
use App\User;
use App\Models\Session;

class SessionController extends Controller 
{
	 /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
        $user = User::getAuthUser();
        $sessions = Session::where('team_id',Input::get('team_id'))->paginate(20);
        
        return response()->json($sessions,200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
        $user = User::getAuthUser();
	    if($user->canCreateSessions(Input::get('team_id'))){
		    $validator = Validator::make($request->all(), [
	            'topic' => 'required',
	            'date' => 'required',
			]);
	
			if ($validator->fails()) {
	           	return response()->json(['message' => $validator->messages()], 400);
			}

	        Session::create(Input::all());
        } else {
	        return response()->json(['message' => 'You do not have permission to create a session for this team'], 400);
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}