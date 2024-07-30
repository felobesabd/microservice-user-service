<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Service\IAuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected IAuthService $authService;
    public function __construct(
        IAuthService $authService
    )
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request) {
        $user = $this->authService->register($request->validated());
        return response()->json(['data'=> $user, 'msg' => 'success', 'code' => 201]);
    }

    public function login(LoginRequest $request) {
        $user = $this->authService->login($request->validated());

        return response()->json(['data'=> $user, 'msg' => 'success', 'code' => 200]);
    }

    public function logout(LoginRequest $request) {
        $user = $this->authService->login($request->validated());

        return response()->json(['data'=> $user, 'msg' => 'success', 'code' => 200]);
    }
}
