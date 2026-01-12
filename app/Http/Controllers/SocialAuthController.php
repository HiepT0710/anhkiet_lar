<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\User as SocialiteUserContract;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    /**
     * Providers that are supported by the UI.
     *
     * @var array<int, string>
     */
    protected array $providers = ['google', 'facebook', 'zalo'];

    public function redirect(string $provider): RedirectResponse
    {
        abort_unless($this->isSupportedProvider($provider), 404);

        $driver = Socialite::driver($provider);

        // Google & Facebook callbacks work better in stateless mode.
        if ($provider !== 'zalo') {
            $driver = $driver->stateless();
        }

        return $driver->redirect();
    }

    public function callback(string $provider): RedirectResponse
    {
        abort_unless($this->isSupportedProvider($provider), 404);

        try {
            $driver = Socialite::driver($provider);

            if ($provider !== 'zalo') {
                $driver = $driver->stateless();
            }

            $socialUser = $driver->user();
        } catch (\Throwable $th) {
            report($th);

            return redirect()->route('login.show')->withErrors([
                'social' => 'Không thể đăng nhập bằng ' . ucfirst($provider) . '. Vui lòng thử lại.',
            ]);
        }

        $user = $this->findOrCreateUser($provider, $socialUser);

        Auth::login($user, true);

        return $user->is_admin
            ? redirect()->route('admin.dashboard')
            : redirect()->intended('/');
    }

    protected function findOrCreateUser(string $provider, SocialiteUserContract $socialUser): User
    {
        $providerId = (string) $socialUser->getId();
        $email = $socialUser->getEmail();
        $avatar = $socialUser->getAvatar();

        $existing = User::where('provider_name', $provider)
            ->where('provider_id', $providerId)
            ->first();

        if ($existing) {
            $existing->fill(array_filter([
                'avatar' => $avatar,
                'email' => $email,
            ]));

            if ($existing->isDirty()) {
                $existing->save();
            }

            return $existing;
        }

        if ($email) {
            $userByEmail = User::where('email', $email)->first();

            if ($userByEmail) {
                $userByEmail->fill([
                    'provider_name' => $provider,
                    'provider_id' => $providerId,
                    'avatar' => $avatar,
                ])->save();

                return $userByEmail;
            }
        }

        $fallbackEmail = $email ?: "{$providerId}@{$provider}.local";
        $displayName = $socialUser->getName()
            ?: $socialUser->getNickname()
            ?: 'Người dùng ' . strtoupper(Str::random(4));

        return User::create([
            'name' => $displayName,
            'email' => $fallbackEmail,
            'password' => Hash::make(Str::random(32)),
            'provider_name' => $provider,
            'provider_id' => $providerId,
            'avatar' => $avatar,
        ]);
    }

    protected function isSupportedProvider(string $provider): bool
    {
        return in_array($provider, $this->providers, true);
    }
}

