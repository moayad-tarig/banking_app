<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PinController extends Controller
{
    public function __construct(private readonly UserService $userService){}

    public function setupPin(Request $request): JsonResponse
    {
        $request->validate([
            'pin' => ['required', 'string', 'min:4', 'max:4']
        ]);
            /** @var User $user */
        $user = $request->user();
        $this->userService->setupPin($user, $request->input('pin'));
        return $this->sendSuccess([], 'Pin is set successfully');
    }

    /**
     * @throws PinNotSetException
     * @throws ValidationException
     */
    public function validatePin(Request $request): JsonResponse
    {
        $request->validate([
            'pin' => ['required', 'string', 'min:4', 'max:4']
        ]);
        /** @var User $user */
        $user = $request->user();
        $isValid = $this->userService->validatePin($user->id, $request->input('pin'));
        return $this->sendSuccess(['is_valid' => $isValid], 'Pin Validation');
    }



}
