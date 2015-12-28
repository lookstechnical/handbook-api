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
use Input;
use App\Model\Team;
use App\Model\Club;

class TeamController extends Controller 
{

	
	public function store(Request $request)
	{
		$club = Club::where('name',Input::get('club_name'));
		if(empty($club)){
			$club = Club::createNew(Input::all());
		}
		
		$team = $club->teams->where('name',Input::get('team_name'));
		if(empty($team)){
			$team = Club::createTeam(Input::all());
		}
		
		return response()->json($games,200);

	}
	
	
}