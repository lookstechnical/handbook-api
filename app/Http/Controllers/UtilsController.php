<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\Drill;
use App\Models\Game;
use App\Models\Club;	
	
class UtilsController extends controller
{
	public function clearAll()
	{
		$users = User::all();
		foreach($users as $u){
			$u->delete();
		}
		
		$drills = Drill::all();
		foreach($drills as $d){
			$d->delete();
		}
		
		$games = Game::all();
		foreach($games as $g){
			$g->delete();
		}
		
		$clubs = Club::all();
		foreach($clubs as $c){
			$c->delete();
		}

	}
}