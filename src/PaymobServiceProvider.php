<?php
 
namespace Ahmedmahfouz\Paymob;

use Illuminate\Support\ServiceProvider;

class PaymobServiceProvider extends ServiceProvider
{

    public function register(){
        //
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->publishes([
            __DIR__.'/../config/paymob.php' => config_path('paymob.php'),
        ], 'paymob-config');

        $this->publishes([
            __DIR__.'/../resources/views/test_paymob_view' => resource_path('views/vendor/test_paymob_view'),
        ], 'paymob-view');
    }

}