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
use App\User;
use App\Models\User\Role;

class MeController extends Controller
{
    public function __construct() {
        $this->middleware('jwt.auth');
    }

    public function index() {
        $user = User::getAuthUser();
        
        $user->setCurrentRole();
        // the token is valid and we have found the user via the sub claim
        return response()->json(compact('user'));
    }

    public function role(Request $request) {


        $user = User::getAuthUser();
        $r = new Role();
        $r->role = $request->get('role');
        $r->team = $request->get('team');
        $r->club = $request->get('club');
        $user->roles()->save($r);

        $user = User::find($user->_id);

        return response()->json(compact('user'));
    }
}