<?php

namespace App\Services;

use InvalidArgumentException;
use App\Repositories\AuthRepository;
use Illuminate\Support\Facades\Validator;

class AuthService{
    private AuthRepository $authRepository;
	public function __construct()
	{
		$this->authRepository = new AuthRepository('user');
	}
    public function getAll()
    {
        $auth = $this->authRepository->getAll();
        return $auth;
    }
    public function logout(){
        $auth = $this->authRepository->logout();
        return $auth;
    }
    public function register($auth){
        $validator = Validator::make($auth, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Return an error response if the validation fails
        if ($validator->fails()) {
            throw new InvalidArgumentException($validator->errors()->first());
        }
        $result = $this->authRepository->register($auth);
        return $result;
    }
}
