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

class PlayersController extends Controller 
{

	
	public function store(Request $request)
	{
		$club = Club::where('_id',$request->get('club_id'))->first();
		
		
		
			$team = $club->teams()->where('_id',$request->get('team_id'))->first();
		
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
			$role->club_id = $club->_id;
			$role->club_name = $club->name;
			$role->team_id = $team->_id;
			$role->team_name = $team->name;
			$role->role = 'player';
			
			$user->roles()->save($role);
			
			return response()->json($user,200);
		

	}
	
		
	
}