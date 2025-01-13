<?php

namespace App\Services;

use App\Dtos\UserDto;
use App\Exceptions\InvalidPinLengthException;
use App\Exceptions\PinHasAlreadyBeenSetException;
use App\Exceptions\PinNotSetException;
use App\Interfaces\UserServicesInterface;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserService implements UserServicesInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function createUser(UserDto $userDto): User
    {
        return User::query()->create([
            'name' => $userDto->getName(),
            'email' => $userDto->getEmail(),
            'phone_number' => $userDto->getPhoneNumber(),
            'password' => $userDto->getPassword(),
        ]);
    }
    public function setupPin(User $user, string $pin): void
    {
        if ($this->hasSetPin($user)) {
            throw new PinHasAlreadyBeenSetException("Pin has already been set");
        }
        if (strlen($pin) != 4) {
            throw new InvalidPinLengthException();
        }

        $user->pin = Hash::make($pin);
        $user->save();
    }
    public function validatePin(int $userId, string $pin): bool
    {
        $user = $this->getUserById($userId);
        if (!$this->hasSetPin($user)) {
            throw new PinNotSetException("Please set your pin");
        }
        return Hash::check($pin, $user->pin);
    }
    public function hasSetPin(User $user): bool
    {
        return $user->pin != null;
    }
    public function  getUserById(int $userId): User
    {
        $user = User::query()->findOrFail($userId);
        if (!$user) {
            throw new  ModelNotFoundException('User not found');
        }
        return $user;
    }
}
