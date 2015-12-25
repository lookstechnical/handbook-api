<?php
/**
 * Created by PhpStorm.
 * User: richardellis
 * Date: 05/11/15
 * Time: 08:44
 */

namespace App\Http\Controllers;

use JWTAuth;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\Drill;
use Input;

class DrillController extends Controller
{
    public function __construct() {
        $this->middleware('jwt.auth');
    }
    
    public function index()
    {
        //
        $drills = Drill::all();
        return response()->json($drills,200);
       
    }
    
    
    public function store(Request $request)
    {
        //
        $drill = Drill::createDrill(Input::all());
        return response()->json($drill,200);
    }
}