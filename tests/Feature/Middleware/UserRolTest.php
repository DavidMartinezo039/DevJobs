<?php

use App\Http\Middleware\UserRol;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Route as RouteFacade;

it('redirects to home if user has developer role', function () {
    $user = new class {
        public function hasRole($role) {
            return $role === 'developer';
        }
    };

    $request = Request::create('/test', 'GET');
    $request->setUserResolver(fn() => $user);

    $next = fn() => 'next called';

    $middleware = new UserRol();

    $response = $middleware->handle($request, $next);

    expect($response)->toBeInstanceOf(\Illuminate\Http\RedirectResponse::class)
        ->and($response->getTargetUrl())->toBe(route('home'));
});

it('calls next if user does not have developer role', function () {
    $user = User::factory()->create();
    $user->assignRole('moderator');

    $request = Request::create('/test', 'GET');
    $request->setUserResolver(fn() => $user);

    $middleware = new UserRol();

    $next = fn($req) => new Response('next called');

    $response = $middleware->handle($request, $next);

    expect($response)->toBeInstanceOf(Response::class)
        ->and($response->getContent())->toBe('next called');
});
