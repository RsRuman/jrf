<?php

namespace App\Repositories;

use App\Interfaces\AuthenticationInterface;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthenticationRepository implements AuthenticationInterface
{
    /**
     * Create user
     * @param array $data
     * @return mixed
     */
    public function createUser(array $data): mixed
    {
        return User::create($data);
    }

    /**
     * Get token
     * @param array $data
     * @return mixed
     */
    public function getToken(array $data): mixed
    {
        if (Auth::attempt($data)) {
            $user = Auth::user();
            return $user->createToken('JRF')->accessToken;
        }

        return false;
    }

    /**
     * Remove token
     * @param User $user
     * @return void
     */
    public function removeToken(User $user): void
    {
        $user->tokens->each(function ($token) {
            $token->revoke();
        });
    }
}
