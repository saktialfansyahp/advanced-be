<?php

namespace App\Repositories;

use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthRepository{
    private User $user;
	public function __construct()
	{
		$this->user = new User([
            'name',
            'email',
            'password'
        ]);
	}
    public function register($auth){
        $name = $auth['name'];
        $email = $auth['email'];
        $password = $auth['password'];

        $auth = User::create([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt('password'),
        ]);

        // Generate a JWT token for the user
        return $auth;
    }
    public function getAll()
    {
        $auth = auth()->user();
        return $auth;
    }
    public function logout(){
        auth()->logout();
    }
}
