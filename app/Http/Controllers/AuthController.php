<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;
use App\Services\AuthService;

class AuthController extends Controller
{
    private AuthService $authService;
	public function __construct() {
		$this->authService = new AuthService();
	}
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Return an error response if the validation fails
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Create a new user record
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        // Generate a JWT token for the user
        $token = JWTAuth::fromUser($user);

        // Return a success response with the JWT token
        return response()->json(['token' => $token], 201);
    }
    public function login(){
        $credentials = request(['email', 'password']);
        $token = auth()->attempt($credentials);
        if(!$token){
            return response()->json(['error'=>'Unauthorized'], 401);
        }

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60
        ]);
    }
    public function logout(){
        $this->authService->logout();

        return response()->json([
            'message' => 'Successfully logged out'
        ], 200);
    }
    public function refresh(){
        return response()->json([
            'access_token' => JWTAuth::refresh(JWTAuth::getToken()),
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60
        ]);
    }
    public function data(){
        $auth = $this->authService->getAll();
		return response()->json($auth);
    }
}
