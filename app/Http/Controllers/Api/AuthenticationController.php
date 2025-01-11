<?php

namespace App\Http\Controllers\Api;

use App\Dtos\UserDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticationController extends Controller
{
    public function __construct(private readonly UserService $userService) {}
    public function register(RegisterUserRequest $request)
    {
        $userDto = UserDto::fromApiFormRequest($request);
        $user = $this->userService->createUser($userDto);

        return $this->sendSuccess($user, 'User Created Successfully');
    }

    /**
     * Login User
     *  
     */
    public function login(LoginRequest $request)
    {
        $creadentials = $request->validated();

        if (!Auth::attempt($creadentials)) {
            return $this->sendError('Invalid Creadentials');
        }
        $user = $request->user();
        $token = $user->createToken('auth-token')->plainTextToken;
        return $this->sendSuccess([
            'user' => $user,
            'token' => $token
        ], 'Login Successfully');
    }


    public function user(Request $request){
        return $request->user();
    }

    public function logout(Request $request){
        $request->user()->tokens()->delete();
        return $this->sendSuccess([], 'Logout Successfully');
    }
}
