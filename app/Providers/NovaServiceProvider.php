<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Laravel\Nova\Cards\Help;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Heading;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;
use OptimistDigital\NovaSettings\NovaSettings;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        NovaSettings::addSettingsFields(function () {
            if (!(bool) \setting('facebook_active')) {
                return [
                    Boolean::make('Active', 'facebook_active'),
                ];
            }

            return [
                Boolean::make('Active', 'facebook_active'),
                Text::make('Client ID', 'facebook_client_id'),
                Text::make('Secret', 'facebook_secret'),
                Heading::make('Connect facebook <a target="_blank" href="' . url('auth/facebook') . '">Connect Now</a>')->asHtml(),
                Text::make('Long Live Token', 'facebook_access_token')
            ];
        }, [], 'Facebook');

        NovaSettings::addSettingsFields([
            Number::make('Max article age', 'google_news_max_age')->placeholder('( in hours )'),
        ], [], 'Google News');

        NovaSettings::addSettingsFields([
            Text::make('Site Url', 'site_url')
        ], [], 'General');
    }

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::routes()
            ->withAuthenticationRoutes()
            ->withPasswordResetRoutes()
            ->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewNova', function ($user) {
            return in_array($user->email, [
                'admin@admin.com'
            ]);
        });
    }

    /**
     * Get the cards that should be displayed on the default Nova dashboard.
     *
     * @return array
     */
    protected function cards()
    {
        return [
            new Help,
        ];
    }

    /**
     * Get the extra dashboards that should be displayed on the Nova dashboard.
     *
     * @return array
     */
    protected function dashboards()
    {
        return [];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        return [
            new NovaSettings
        ];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
