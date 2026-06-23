<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\User;
use App\Services\JwtService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request, JwtService $jwtService): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
        ]);

        $user = User::query()->where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => 'Email atau password tidak valid.',
            ]);
        }

        $ttl = $request->boolean('remember')
            ? config('jwt.remember_ttl', config('jwt.ttl'))
            : config('jwt.ttl');

        $token = $jwtService->issueToken($user, $ttl);

        return redirect()
            ->route($this->dashboardRoute($user))
            ->with('success', 'Login berhasil, selamat datang kembali.')
            ->cookie(
                config('jwt.cookie_name'),
                $token,
                $ttl,
                null,
                null,
                false,
                true,
                false,
                'Lax',
            );
    }

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function register(Request $request, JwtService $jwtService): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['nullable', Rule::in([UserRole::Customer->value])],
        ]);

        $user = User::query()->create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => UserRole::Customer,
        ]);

        $token = $jwtService->issueToken($user);

        return redirect()
            ->route('home')
            ->with('success', 'Akun berhasil dibuat. Selamat datang, silakan jelajahi catalog kami.')
            ->cookie(
                config('jwt.cookie_name'),
                $token,
                config('jwt.ttl'),
                null,
                null,
                false,
                true,
                false,
                'Lax',
            );
    }

    public function logout(): RedirectResponse
    {
        return redirect()
            ->route('home')
            ->with('success', 'Anda telah keluar dari sistem.')
            ->withoutCookie(config('jwt.cookie_name'));
    }

    public function dashboard(Request $request): RedirectResponse
    {
        return redirect()->route($this->dashboardRoute($request->user()));
    }

    private function dashboardRoute(User $user): string
    {
        return match ($user->role) {
            UserRole::Admin => 'admin.dashboard',
            UserRole::Owner => 'owner.dashboard',
            default         => 'home',
        };
    }
}
