<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;
use Faker\Generator as FakerGenerator;
use Faker\Factory as FakerFactory;

// AppServiceProvider 是框架的核心，在 Laravel 启动时，会最先加载该文件。
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // $date->diffForHumans() 为我们生成的时间是英文的，如果要使用中文时间，则需要对 Carbon 进行本地化设置
        Carbon::setLocale('zh');

        // 模型工厂本地化
        $this->app->singleton(FakerGenerator::class,function(){
            return FakerFactory::create("zh_CN");
        });
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
