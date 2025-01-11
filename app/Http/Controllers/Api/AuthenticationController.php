<?php

namespace App\Http\Controllers\Api;

use App\Dtos\UserDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;

class AuthenticationController extends Controller
{
    public function __construct(private readonly UserService $userService)
    {
        
    }
    public function register(RegisterUserRequest $request){
        $userDto = UserDto::fromApiFormRequest($request);
        $user = $this->userService->createUser($userDto);

        return $this->sendSuccess($user , 'User Created Successfully');

        
    }
}
