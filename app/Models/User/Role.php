<?php
namespace App\Models\User;

use Jenssegers\Mongodb\Model as Eloquent;
use App\Models\Club;
use App\Models\Club\Team;

class Role Extends Eloquent
{
	protected $collection = 'roles';
	 
	public static function createNew($data)
	{
		
			//get club if exists or create
		$club = Club::where('name',$data['club'])->first();
		if(empty($club)){
			$club = Club::createNew($data);
		}
		
		$team = $club->teams()->where('name',$data['team'])->first();
		if(empty($team)){
			$team = $club->createNewTeam($data);
		}
		
		$r = new Role();
		$r->club = $club->toArray();
		$r->team = $team->toArray();
		$r->role = $data['role'];
		
		return $r;
	}
}
