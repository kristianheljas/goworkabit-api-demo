<?php

namespace App\Providers;

use App\JsonApi\ExceptionParser;
use CloudCreativity\LaravelJsonApi\Contracts\Exceptions\ExceptionParserInterface;
use CloudCreativity\LaravelJsonApi\LaravelJsonApi;
use Illuminate\Support\ServiceProvider;

class JsonApiServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ExceptionParserInterface::class, ExceptionParser::class);

        LaravelJsonApi::defaultApi('v1');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
