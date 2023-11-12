<?php

namespace App\Providers;

use App\Models\Author;
use App\Models\Category;
use App\Models\Quote;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer(['layouts.app', 'dashboard.layouts.app'], function ($view) {
            $view->with('route', Route::currentRouteName());
        });

        View::composer('layouts.footer', function ($view) {
            $options = array('http' => array('method' => 'GET', 'header' => array("Content-Type: application/x-yametrika+json", "Authorization: OAuth y0_AgAAAABIQXDlAAkDKgAAAADZ-6F_9814HQFDRW6m65YSwpga5ZzKS0c")));
            $context = stream_context_create($options);
            $url = "https://api-metrika.yandex.net/stat/v1/data?id=91119741&metrics=ym:pv:pageviews&date1=2022-01-01&filters=(ym:pv:URL=='https://durdonaho.tj/')";
            $metrikaRequest = file_get_contents($url, false, $context);
            $siteViews = intval((json_decode($metrikaRequest))->data[0]->metrics[0]);

            if($siteViews < 1000) {
                $siteViews = str_pad($siteViews, 4, '0', STR_PAD_LEFT);
            }

            $view->with('siteViews', str_split($siteViews));
        });

        View::composer('components.aside-categories', function ($view) {
            $view->with('categories', Category::approved()->orderBy('title')->get());
        });

        View::composer('components.popular-categories', function ($view) {
            $view->with('categories', Category::where('popular', true)->approved()->inRandomOrder()->get());
        });

        View::composer('components.aside-popularity', function ($view) {
            $view->with('quote', Quote::where('popular', true)->approved()->inRandomOrder()->first())
                ->with('author', Author::where('popular', true)->approved()->inRandomOrder()->first());
        });

        View::composer('components.filter-categories', function ($view) {
            $view->with('categories', Category::approved()->orderBy('title')->get());
        });

        View::composer(['dashboard.layouts.aside', 'dashboard.layouts.new-quote-notification'], function ($view) {
            $view->with('unverifiedQuotesCount', Quote::where('verified', false)->count());
        });
    }
}
