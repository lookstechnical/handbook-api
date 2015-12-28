<?php
namespace App\Models;

use Jenssegers\Mongodb\Model as Eloquent;
use App\Models\Club\Team;

class Team Extends Eloquent
{
	$collection = 'teams';	
}