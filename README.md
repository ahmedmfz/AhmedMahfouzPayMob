
# Laravel PayMob Package
####Note: This is a version Beta and I wish to complete all my ideas in this package.

sample package for laravel applications to integrate with PayMob payment gateway

# Hi, I'm Ahmed MAhfouz! 👋


## 🚀 About Me
I'm a Software Engineer...


## Installation

Install with composer

```bash
  composer require ahmedmahfouz/paymob
```

Add service Provider in config/app.php

```bash
\Ahmedmahfouz\Paymob\PaymobServiceProvider::class,
```

    
## Environment Variables

To run this package, you will need to add the following environment variables to your .env file

`PAYMOBTOKEN`

`VISAINTEGRATIONID`

`VISAIFRAMEID`

`WALLETINTEGRATIONID`

`AMANINTEGRATIONID`

`PAYMOBHMAC`


OR run command:
```bash
 php artisan vendor:publish --tag=paymob-config
```

## view for testing  ?
# /paymob-check-view

```bash
 php artisan vendor:publish --tag=paymob-view
```
## How to use ?

### example for pay

```bash
        $amout   = 50 ;
        $billing = ['first_name' => 'ahmed' , 'last_name'=> 'mohamed' , 'email'=> 'admin@admin.com' , 'phone_number' => '01000000000' ];
        $orderId = rand(1,3000);
        $service = $request->service;
        
        if($request->service == 'MOBILE_WALLET'){
            $integrationId = env('WALLETINTEGRATIONID');
        }else{
            $integrationId = env('VISAINTEGRATIONID');
        }
        return  (new PayMobService)->pay($amout , $billing , $orderId , $service  ,$integrationId);
```


### example for checkout
```bash
return  (new PayMobService)->callback($request);

```
## Support

For support, email ahmedmahfouz2060@gmail.com 
