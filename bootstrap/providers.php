<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\EventServiceProvider::class,
    App\Providers\SocialiteServiceProvider::class,
    App\Providers\VKontakteServiceProvider::class,
    App\Providers\YandexServiceProvider::class,
    Laravel\Socialite\SocialiteServiceProvider::class,
    SocialiteProviders\Manager\ServiceProvider::class,
    Barryvdh\DomPDF\ServiceProvider::class,
    SimpleSoftwareIO\QrCode\QrCodeServiceProvider::class,
];
