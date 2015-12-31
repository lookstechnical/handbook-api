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
		var_dump($request->club_id));die();
		$club = Club::where('_id',$request->club_id)->first();
		
		
		
			$team = $club->teams()->where('_id',$request->team_id)->first();
		
			$user = User::where('email',$request->email)->first();
			if(empty($user)){
				$user = new User();
				$user->first_name = $request->first_name;
				$user->last_name = $request->last_name;
				$user->email = $request->email;
				$user->position = $request->position;
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