<?php


namespace App\Repository\Imp;

use App\Models\User;
use App\Repository\IUserRepo;
use Illuminate\Support\Facades\Hash;

class UserRepository implements IUserRepo
{
    public function createUser(string $username, string $email, string $password): User
    {
        return User::create([
            'username' => $username,
            'email' => $email,
            'password' => Hash::make($password),
        ]);
    }

    public function getUserByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function getUserById(int $id): ?User
    {
        return User::where('id', $id)->get()->first();
    }
}
