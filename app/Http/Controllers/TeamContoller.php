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

class TeamController extends Controller 
{

    public function show($id)
    {
    	$team = DB::collection('clubs')->where(
			'teams._id', $id)->project(array( 'teams.$' => 1 ) )->get();
		return response()->json($team,200);
    }
	
	public function store(Request $request)
	{
		$club = Club::where('name',$request->get('club_name'))->first();
		if(empty($club)){
			$club = Club::createNew($request->all());
		}
		
		$team = $club->teams()->where('name',$request->get('team_name'))->first();
		if(empty($team)){
			$team = $club->createNewTeam($request->all());
		}
		
		// team exists and has a user add pnding user and notify original creator that user wants access
		
		// else add user to team 
		
		$user = User::where('_id', $request->get('user_id'))->first();
		
		$role = new Role();
		$role->club_id = $club->_id;
		$role->club_name = $club->name;
		$role->team_id = $team->_id;
		$role->team_name = $team->name;
		$role->role = 'coach';
		
		$user->roles()->save($role);
		
		return response()->json($team,200);

	}
	
	
	
	
}