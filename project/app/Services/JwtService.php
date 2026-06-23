<?php

namespace App\Services;

use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class JwtService
{
    public function issueToken(User $user, ?int $ttl = null): string
    {
        $ttl ??= config('jwt.ttl');

        $issuedAt = CarbonImmutable::now();
        $expiresAt = $issuedAt->addMinutes($ttl);

        $header = $this->base64UrlEncode(json_encode([
            'alg' => 'HS256',
            'typ' => 'JWT',
        ], JSON_THROW_ON_ERROR));

        $payload = $this->base64UrlEncode(json_encode([
            'iss' => config('jwt.issuer'),
            'sub' => $user->id,
            'email' => $user->email,
            'role' => $user->role->value,
            'name' => $user->name,
            'iat' => $issuedAt->timestamp,
            'exp' => $expiresAt->timestamp,
        ], JSON_THROW_ON_ERROR));

        $signature = $this->base64UrlEncode(hash_hmac('sha256', $header.'.'.$payload, $this->secret(), true));

        return $header.'.'.$payload.'.'.$signature;
    }

    public function decode(?string $token): ?array
    {
        if (! $token || substr_count($token, '.') !== 2) {
            return null;
        }

        [$encodedHeader, $encodedPayload, $encodedSignature] = explode('.', $token);

        $expectedSignature = $this->base64UrlEncode(
            hash_hmac('sha256', $encodedHeader.'.'.$encodedPayload, $this->secret(), true),
        );

        if (! hash_equals($expectedSignature, $encodedSignature)) {
            return null;
        }

        $payload = json_decode($this->base64UrlDecode($encodedPayload), true);

        if (! is_array($payload) || empty($payload['exp']) || now()->timestamp >= (int) $payload['exp']) {
            return null;
        }

        return $payload;
    }

    public function tokenFromRequest(Request $request): ?string
    {
        $bearerToken = $request->bearerToken();

        if ($bearerToken) {
            return $bearerToken;
        }

        return $request->cookie(config('jwt.cookie_name'));
    }

    private function secret(): string
    {
        $appKey = (string) config('app.key');

        if (Str::startsWith($appKey, 'base64:')) {
            return base64_decode(Str::after($appKey, 'base64:'), true) ?: $appKey;
        }

        return $appKey;
    }

    private function base64UrlEncode(string $value): string
    {
        return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
    }

    private function base64UrlDecode(string $value): string
    {
        return base64_decode(strtr($value, '-_', '+/'), true) ?: '';
    }
}
