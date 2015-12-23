<?php
namespace App\Models;
use Jenssegers\Mongodb\Model as Eloquent;

class Session extends Eloquent
{
	protected $collection = "games";
	
	
	public static function createSession($data)
	{
		$game = new Game();
		
		$game->team_id = $data['team_id'];
		$game->author = $data['user_id'];
		$game->date = $data['date'];
		$game->topic = $data['topic'];
		$game->aims = $data['aims'];
		

		$game->save();
		
		return $game;
		
	}
	
	public function registerPlayer($user_id)
	{
		$this->push('register',$user_id,true);
	}
	
	public function addGameDrill($gameDrill)
	{
		$this->push('games_drills',$gameDrill);
	}
}