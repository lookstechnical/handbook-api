<?php
namespace App\Models\User;

use Jenssegers\Mongodb\Model as Eloquent;
use App\Models\Club;
use App\Models\Club\Team;

class Role Extends Eloquent
{
	protected $collection = 'roles';
	 
	public static function createNew($data,$club,$team)
	{
				
		$r = new Role();
		$r->club = $club->toArray();
		$r->team = $team->toArray();
		$r->role = $data['role'];
		
		return $r;
	}
}
