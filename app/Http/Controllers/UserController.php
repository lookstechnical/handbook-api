<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Input;
use App\User;
use App\Models\User\Role;
use Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
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
    
    public function login()
    {
    	$data = Input::all();
    	$user = User::where('username',$data['username'])->first();
    	if(empty($user)){
	    	$return['data'] = null;
	   		$return['info_message'] = 'Username does not exist';
	    	$return['status'] = 404;
			return response()->json($return,404);
    	}
    	
    	if(Hash::check($data('password'), $user->password) === false)
		{
			$return['data'] = null;
	   		$return['info_message'] = 'Password Incorrect';
	    	$return['status'] = 500;
			return response()->json($return,500);
		}


    	return response()->json($user,200);
    }
    
    public function storeRole()
    {
	    $user_id = Input::get('_id');

	    $roleData = Input::get('new');
		$roleData = $roleData['role'];
    	$user = User::where('_id',$user_id)->first();
    	
    	$role = Role::createNew($roleData);
    	
    	$user->roles()->save($role);
    	
    	return response()->json($user,200);
    }
    
    public function fbLogin()
    {
    	$user = User::where('email',Input::get('email'))->first();
    	
    	if(empty($user)){
	    	$return['data'] = null;
	    	$return['message'] = 'Not Registered';
	    	$return['status'] = 404;
	    	 
	    	return response()->json($return,404);
    	}
    	
    	// check password matches else return error
    	//var_dump(Hash::check('password', Input::get('password')));
    	if(Hash::check(Input::get('id'), $user->password) === false)
		{
			$return['data'] = null;
	   		$return['message'] = 'Password Incorrect';
	    	$return['status'] = 500;
			return response()->json($return,500);
		}
    		
    	//if matches return user model     	
    	return response()->json($user,200);
    }
    
    public function register()
    {
	    
	    $existing_user = User::where('email',Input::get('email'))->first();
	    if($existing_user){
		    $return['data'] = null;
	   		$return['message'] = 'User already exists';
	    	$return['status'] = 500;
			return response()->json($return,500);
	    }
	    
    	$user = new User();
    	$user->email =  Input::get('email');
    	$user->password = Hash::make(Input::get('password'));
    	$user->first_name = Input::get('first_name');
    	$user->last_name = Input::get('last_name');
    	$user->dob = Input::get('birthday');
    	
		if(!empty(Input::get('profile_picture'))){
    		$user->profile_picture = Input::get('profile_picture');
    	}
    	
    	if(!empty(Input::get('fb_id'))){
    		$user->fb_id = Input::get('fb_id');
    	}
    	
    	$user->save();
    	
    	return $user;
    	
    }
}
