<?php
namespace App\Models;

use Jenssegers\Mongodb\Model as Eloquent;
use App\Models\Club\Team;

class Club Extends Eloquent
{
	protected $collection = 'clubs';
	 
	public static function createNew($data)
	{
		$c = new Club();
		$c->name = $data['club_name'];
		$c->save();
		return $c;
	}
	
	public function createNewTeam($data)
	{
		$t = new Team();
		$t->name = $data['team'];		
		$this->teams()->save($t);
		return $t;
	}
	
	public function teams()
	{
		return $this->embedsMany('App\Models\Club\Team','teams');
	}
}
