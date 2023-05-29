<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use App\Services\AuthService;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    private AuthService $authService;
	public function __construct() {
		$this->authService = new AuthService();
	}
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'address' => 'required|string|max:255',
            'role' => 'required|string|in:admin,customer',
            'kota' => 'required|string|max:255',
            'status' => 'required',
            'no_telp' => 'required',
            'jenis' => 'required',
        ]);

        // Return an error response if the validation fails
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Create a new user record
        $user = User::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'username' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'address' => $request->address,
            'role' => $request->role,
        ]);

        $data = Pelanggan::create([
            'user_id' => $user->id,
            'kota' => $request->kota,
            'status' => $request->status,
            'no_telp' => $request->no_telp,
            'jenis' => $request->jenis,
        ]);

        // Generate a JWT token for the user
        $token = JWTAuth::fromUser($user);
        $data = $user;
        // Return a success response with the JWT token
        return response()->json([
            'data' => $data,
            'token' => $token,
        ], 201);
    }
    public function login(){
        $credentials = request(['email', 'password']);
        $token = auth()->attempt($credentials);
        if(!$token){
            return response()->json(['error'=>'Invalid Email or Password'], 401);
        }
        $data = $this->authService->getAll();
        $token = JWTAuth::attempt($credentials, ['expires_in' => 3600]);

        return response()->json([
            'data' => $data,
            'access_token' => $token,
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
    public function dataUser(){
        $auth = $this->authService->getUser();
		return response()->json($auth);
    }
    public function update(Request $request, $id){

        $data = [
            'firstname' => $request->input('firstname'),
            'lastname' => $request->input('lastname'),
            'username' => $request->input('username'),
            'address' => $request->input('address'),
            'email' => $request->input('email'),
            'role' => $request->input('role')
        ];

        $data2 = [
            'no_telp' => $request->input('pelanggan.no_telp'),
            'kota' => $request->input('pelanggan.kota'),
            'status' => $request->input('pelanggan.status'),
            'jenis' => $request->input('pelanggan.jenis'),
        ];

        $users = User::find($id);
        $users->update($data);

        $pelanggan = Pelanggan::where('user_id', $id)->first();
        $pelanggan->update($data2);

        return response()->json([
            'users' => $users,
            'pelanggan' => $pelanggan
        ]);


    }
    public function destroy($id){
        $user = User::find($id);
        $user->delete();
    }
}
