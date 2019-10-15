<?php
namespace Xw\Weather;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    protected $defer = true;

    public function register()
    {
        $this->app->singleton(Weather::class, function(){
            return new Weather(config('weather.weather_key'));
        });

        $this->app->alias(Weather::class, 'weather');
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/Config/weather.php' => config_path('weather.php')
        ]);
    }

    public function provides()
    {
        return [Weather::class, 'weather'];
    }
}