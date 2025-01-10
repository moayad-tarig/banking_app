<?php

namespace App\Services;

use App\Dtos\UserDto;
use App\Models\User;
use Illuminate\Http\Request;

class UserService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function createUser(UserDto $userDto) : User{
        return User::query()->create([
            'name' => $userDto->getName(),
            'email' => $userDto->getEmail(),
            'phone_number' => $userDto->getPhoneNumber(),
            'password' => $userDto->getPassword(),
        ]);

    }
}
