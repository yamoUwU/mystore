<?php

namespace Botble\SocialLogin\Services;

use Botble\SocialLogin\Models\SocialLogin;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class SocialLoginService
{
    public function getSocialLogin(Model $user, string $provider): ?SocialLogin
    {
        return SocialLogin::query()
            ->where('user_id', $user->getKey())
            ->where('user_type', $user::class)
            ->where('provider', $provider)
            ->first();
    }

    public function hasSocialLogin(Model $user, string $provider): bool
    {
        return SocialLogin::query()
            ->where('user_id', $user->getKey())
            ->where('user_type', $user::class)
            ->where('provider', $provider)
            ->exists();
    }

    public function findSocialLoginByProviderAndId(string $provider, string $providerId): ?SocialLogin
    {
        return SocialLogin::query()
            ->where('provider', $provider)
            ->where('provider_id', $providerId)
            ->first();
    }

    public function addSocialLogin(Model $user, array $data): SocialLogin
    {
        return $this->createOrUpdateSocialLogin($user, $data);
    }

    public function createOrUpdateSocialLogin(Model $user, array $data): SocialLogin
    {
        $data['user_id'] = $user->getKey();
        $data['user_type'] = $user::class;

        // First, check if this provider_id is already used
        $existingSocialLogin = $this->findSocialLoginByProviderAndId($data['provider'], $data['provider_id']);

        if ($existingSocialLogin) {
            // Update the existing record to point to the current user
            $existingSocialLogin->update($data);

            return $existingSocialLogin;
        }

        // Check if the user already has a social login for this provider (different provider_id)
        $userSocialLogin = SocialLogin::query()
            ->where('user_id', $user->getKey())
            ->where('user_type', $user::class)
            ->where('provider', $data['provider'])
            ->first();

        if ($userSocialLogin) {
            // Update the existing user's social login with new provider_id
            $userSocialLogin->update($data);

            return $userSocialLogin;
        }

        // Create new social login
        return SocialLogin::query()->create($data);
    }

    public function updateSocialLogin(Model $user, string $provider, array $data): bool
    {
        return SocialLogin::query()
            ->where('user_id', $user->getKey())
            ->where('user_type', $user::class)
            ->where('provider', $provider)
            ->update($data);
    }

    public function removeSocialLogin(Model $user, string $provider): bool
    {
        return SocialLogin::query()
            ->where('user_id', $user->getKey())
            ->where('user_type', $user::class)
            ->where('provider', $provider)
            ->delete();
    }

    public function getSocialLogins(Model $user): Collection
    {
        return SocialLogin::query()
            ->where('user_id', $user->getKey())
            ->where('user_type', $user::class)
            ->get();
    }

    public function findUserByProvider(string $provider, string $providerId): ?Model
    {
        $socialLogin = SocialLogin::query()
            ->where('provider', $provider)
            ->where('provider_id', $providerId)
            ->first();

        if (! $socialLogin) {
            return null;
        }

        return $socialLogin->user;
    }

    public function findUserByEmail(string $email, string $modelClass): ?Model
    {
        return $modelClass::query()->where('email', $email)->first();
    }

    public function createSocialLoginData(array $oAuthData): array
    {
        return [
            'provider' => $oAuthData['provider'],
            'provider_id' => $oAuthData['id'],
            'token' => $oAuthData['token'],
            'refresh_token' => $oAuthData['refresh_token'] ?? null,
            'token_expires_at' => $oAuthData['expires_in'] ? Carbon::now()->addSeconds((int) $oAuthData['expires_in']) : null,
            'provider_data' => [
                'name' => $oAuthData['name'],
                'email' => $oAuthData['email'],
                'avatar' => $oAuthData['avatar'],
            ],
        ];
    }
}
