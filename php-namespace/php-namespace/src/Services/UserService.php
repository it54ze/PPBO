<?php

namespace App\Services;

use App\Models\User;

class UserService {
    public function createUser($name, $email): User {
        return new User($name, $email);
    }
    public function displayUser(User $user): string {
        return "Nama: " . $user->getName()
        . "<br>Email: " . $user->getEmail();
    }
}