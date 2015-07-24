<?php

Route::controllers([
    'auth' => 'AuthController',
    'password' => 'PasswordController',
]);

Route::get('/home', [
    'as' => 'home',
    'middleware' => 'auth',
    'uses' => function () {
        return auth()->user()->email.': ログインしました。';
    },
]);
