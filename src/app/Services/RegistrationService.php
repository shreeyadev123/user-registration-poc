<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegistrationService
{
    public function createUser(array $data):User
    {
        return User::create([
            'name' => trim(($data['firstName'] ?? '') . ' ' . ($data['lastName'] ?? '')),
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

    }
}
