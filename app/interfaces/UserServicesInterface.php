<?php
namespace App\Interfaces;

use App\Dtos\UserDto;
use App\Models\User;

interface UserServicesInterface
{
    

    public function createUser(UserDto $userDto) : User;
    public function  getUserById(int $userId): User;
    public function setupPin(User $user, string $pin) : void;
    public function validatePin(int $userId , string $pin) : bool;
    public function hasSetPin(User $user) : bool;









}