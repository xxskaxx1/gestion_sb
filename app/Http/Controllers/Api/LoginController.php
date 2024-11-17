<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return User::latest()->paginate();
    }

    public function show($user1)
    {
        //Traer todos los usuarios con sus roles
        $allUsersWithAllTheirDirectPermissions  = User::with('permissions')->get();
        $resultado = User::find($user1);
        return $allUsersWithAllTheirDirectPermissions ;
    }

    public function login(Request $request){
        $this->validateLogin($request);
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(
                ['message' => 'Unauthorized'], 401
            );
        }
        return response()->json([
        'token' => $request->user()->createToken($request->device)->plainTextToken,
        'message' => 'Success'
        ]);
    }

    public function validateLogin(Request $request){
        return $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device' => 'required'
        ]);
    }
}
