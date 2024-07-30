<?php


namespace App\Repository;

use App\Models\User;

interface IUserRepo
{
    public function createUser(string $username, string $email, string $password): User;
    public function getUserByEmail(string $email): ?User;
    public function getUserById(int $id);
}
