<?php

namespace App\Interfaces;

use App\Models\User;

interface AuthenticationInterface
{
    public function createUser(array $data);

    public function getToken(array $data);

    public function removeToken(User $user);
}
