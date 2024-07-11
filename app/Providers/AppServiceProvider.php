<?php

namespace App\Providers;

use App\Http\Contracts\RepositoryProvider;
use App\Services\GithubProvider;
use App\Services\GitlabProvider;
use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}
}
