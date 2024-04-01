<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth;


use App\Exceptions\Http\InvalidCredentialsException;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTGuard;

class AuthController extends Controller
{
    /**
     * @param JWTGuard $authGuard
     */
    public function __construct(private Guard $authGuard)
    {
    }

    public function login(Request $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');

        if (! $token = $this->authGuard->attempt($credentials)) {
            throw new InvalidCredentialsException();
        }

        return $this->respondWithToken($token);
    }

    public function refresh(Request $request)
    {
        try {
            $newToken = $this->authGuard->refresh();

            return $this->respondWithToken($newToken);
        } catch (\Exception $exception) {
            return response()->json(['error' => 'Failed to refresh token'], 401);
        }
    }

    protected function respondWithToken($token): JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
