<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Services\JwtService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class HydrateJwtUser
{
    public function __construct(private readonly JwtService $jwtService)
    {
    }

    public function handle(Request $request, Closure $next): Response
    {
        $token = $this->jwtService->tokenFromRequest($request);
        $payload = $this->jwtService->decode($token);

        if ($payload && isset($payload['sub'])) {
            $user = User::query()->find($payload['sub']);

            if ($user) {
                Auth::setUser($user);
                $request->setUserResolver(fn () => $user);
                $request->attributes->set('jwt_payload', $payload);
            }
        }

        return $next($request);
    }
}
