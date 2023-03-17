<?php

namespace App\Repositories;

use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthRepository{
    private User $user;
	public function __construct()
	{
		$this->user = new User([
            'firstname',
            'lastname',
            'username',
            'email',
            'password',
            'address',
            'role',
        ]);
	}
    public function register($auth){
        $firstname = $auth['firstname'];
        $lastname = $auth['lastname'];
        $username = $auth['username'];
        $email = $auth['email'];
        $password = $auth['password'];
        $address = $auth['address'];
        $role = $auth['role'];

        $auth = User::create([
            'firstname' => $firstname,
            'lastname' => $lastname,
            'username' => $username,
            'email' => $email,
            'password' => bcrypt($password),
            'address' => $address,
            'role' => $role,
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
