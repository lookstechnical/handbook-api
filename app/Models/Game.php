<?php
namespace App\Models;
use Jenssegers\Mongodb\Model as Eloquent;

class Game extends Eloquent
{
	protected $collection = "games";
	
	
	public static function createGame($userRole,$data)
	{
		$game = new Game();
		
		$game->team_id = $userRole;
		$game->home_team = $data['home_team'];
		$game->away_team = $data['away_team'];
		$game->date = $data['date'];
		$game->location = $data['location'];

		$game->save();
		
		return $game;
		
	}
}