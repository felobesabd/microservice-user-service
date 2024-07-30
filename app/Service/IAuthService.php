<?php


namespace App\Service;


interface IAuthService
{
    public function register(array $data);
    public function login(array $data);
    public function logout(array $data);
}
