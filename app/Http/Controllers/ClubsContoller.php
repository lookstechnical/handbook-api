<?php
/**
 * Created by PhpStorm.
 * User: richardellis
 * Date: 04/11/15
 * Time: 23:35
 */


namespace App\Http\Controllers;

use JWTAuth;
use App\Http\Controllers\Controller;
use Config;
use Validator;
use Illuminate\Http\Request;
use App\User;
use App\Models\Team;
use App\Models\Club;
use App\Models\User\Role;

class ClubsController extends Controller 
{

	public function index()
	{
		$clubs = Club::all();
		
		return response()->json($clubs,200);
	}
	public function store(Request $request)
	{
		

	}
	
		
	
}