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
use Input;

class PlayersController extends Controller 
{

	
	public function store(Request $request)
	{
		var_dump($request->get('club_id'));die();
		$club = Club::where('_id',Input::get('club_id'))->first();
		
		
		
			$team = $club->teams()->where('_id',Input::get('team_id'))->first();
		
			$user = User::where('email',Input::get('email'))->first();
			if(empty($user)){
				$user = new User();
				$user->first_name = Input::get('first_name');
				$user->last_name = Input::get('last_name');
				$user->email = Input::get('email');
				$user->position = Input::get('position');
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