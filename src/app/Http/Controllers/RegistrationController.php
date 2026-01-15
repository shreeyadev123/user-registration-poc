<?php

namespace App\Http\Controllers;

use App\Requests\RegisterUserRequest;
use App\Services\EmailService;
use App\Services\RegistrationService;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{

    protected RegistrationService $registrationService;
    public function __construct(RegistrationService $registrationService)
    {
        $this->registrationService = $registrationService;
    }

    public function register(RegisterUserRequest $request)
    {
        $validated = $request->validated();
        $user = $this->registrationService->createUser($validated);
        return response()->json([
            'message' => 'User registered successfully.',
            'user'    => $user
        ], 201);
    }
}
