<?php
namespace Xw\Weather;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{

    /**
     *标记服务提供者是否延迟加载
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * 注册服务提供者
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Weather::class, function(){
            return new Weather(config('weather.weather_key'));
        });

        $this->app->alias(Weather::class, 'weather');
    }

    /**
     * 在容器中注册绑定。
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/Config/weather.php' => config_path('weather.php')
        ]);
    }

    /**
     * 获取由提供者提供的服务
     *
     * @return array
     */
    public function provides()
    {
        return [Weather::class, 'weather'];
    }
}