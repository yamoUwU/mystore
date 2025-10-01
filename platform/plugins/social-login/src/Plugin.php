<?php

namespace Botble\SocialLogin;

use Botble\PluginManagement\Abstracts\PluginOperationAbstract;
use Botble\Setting\Facades\Setting;
use Illuminate\Support\Facades\Schema;

class Plugin extends PluginOperationAbstract
{
    public static function remove(): void
    {
        Schema::dropIfExists('social_logins');

        Setting::delete([
            'social_login_enable',
            'social_login_facebook_enable',
            'social_login_facebook_app_id',
            'social_login_facebook_app_secret',
            'social_login_github_enable',
            'social_login_github_app_id',
            'social_login_github_app_secret',
            'social_login_google_enable',
            'social_login_google_app_id',
            'social_login_google_app_secret',
            'social_login_linkedin_enable',
            'social_login_linkedin_app_id',
            'social_login_linkedin_app_secret',
            'social_login_apple_enable',
            'social_login_apple_app_id',
            'social_login_apple_app_secret',
            'social_login_x_enable',
            'social_login_x_app_id',
            'social_login_x_app_secret',
        ]);
    }
}
