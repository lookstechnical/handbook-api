<?php
namespace App\Models;
use Jenssegers\Mongodb\Model as Eloquent;

class Drill extends Eloquent
{
	protected $collection = "drills";
	
	
	public static function createDrill($data)
	{
		$drill = new Drill();
		
		$drill->title = $data['title'];
		$drill->description = $data['description'];
		$drill->media = $data['media'];
		$drill->instructions = $data['instructions'];
		$drill->coaching_points = $data['coaching_points'];
		$drill->access = $data['private'];
		$drill->user = 
		

		$drill->save();
		
		return $drill;
		
	}
}