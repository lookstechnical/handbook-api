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
		
		$user = User::where('_id', $request->get('user_id'));
		
		$role = new Role();
		$role->club = $club->toArray();
		$role->team = $team->toArray();
		$role->role = 'coach';
		
		$user->roles()->save($role);
		
		return response()->json($team,200);

	}
	
	// add a user for player if not exists or add existing user to team
	public function addPlayer(Request $request)
	{
		$club = Club::find($request->get('club_id'));
		
		$team = $club->teams()->where('_id','team_id');
		
		$user = User::where('email',$request->get('email'))->first();
		if(empty($user)){
			$user = new User();
			$user->first_name = $request->get('first_name');
			$user->last_name = $request->get('last_name');
			$user->email = $request->get('email');
			$user->position = $request->get('position');
			$user->active = false;
			$user->save();
		}
		
		//team
		$role = new Role();
		$role->club = $club->toArray();
		$role->team = $team->toArray();
		$role->role = 'player';
		
		$user->roles()->save($role);
		
		return response()->json($user,200);
		
	}
	
	
}