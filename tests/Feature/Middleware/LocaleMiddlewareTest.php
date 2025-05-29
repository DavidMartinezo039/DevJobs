<?php

use App\Http\Middleware\LocaleMiddleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;

it('sets the app locale from the route locale parameter and forgets it', function () {
    Route::middleware(LocaleMiddleware::class)->get('/{locale}/test', function () {
        return 'OK';
    })->name('test.locale');

    $response = $this->get('/es/test');

    $response->assertOk();

    expect(App::getLocale())->toBe('es');
});
