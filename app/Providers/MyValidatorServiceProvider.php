<?php namespace Siakad\Providers;

use Siakad\Services\MyValidator;
use Illuminate\Support\ServiceProvider;

class MyValidatorServiceProvider extends ServiceProvider{

    public function boot()
    {
        \Validator::resolver(function($translator, $data, $rules, $messages)
        {
            return new MyValidator($translator, $data, $rules, $messages);
        });
    }

    public function register()
    {
    }
}