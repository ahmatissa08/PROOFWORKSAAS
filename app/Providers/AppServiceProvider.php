<?php

namespace App\Providers;

use App\Models\Client;
use App\Models\Integration;
use App\Models\Project;
use App\Models\Report;
use App\Policies\ClientPolicy;
use App\Policies\IntegrationPolicy;
use App\Policies\ProjectPolicy;
use App\Policies\ReportPolicy;
use App\Services\AiSummaryService;
use App\Services\ReportGeneratorService;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
        Project::class => ProjectPolicy::class,
        Report::class => ReportPolicy::class,
        Client::class => ClientPolicy::class,
        Integration::class => IntegrationPolicy::class,
    ];

    public function register(): void
    {
        $this->app->singleton(ReportGeneratorService::class);
        $this->app->singleton(AiSummaryService::class);
    }

    public function boot(): void
    {
        $this->registerPolicies();

        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        Password::defaults(fn () => Password::min(10)
            ->letters()
            ->mixedCase()
            ->numbers()
            ->symbols());
    }
}
