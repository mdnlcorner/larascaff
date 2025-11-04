<?php

return [

    'title' => 'Welcome',

    'heading' => 'Please sign in',

    'actions' => [

        'register' => [
            'question' => 'Don\'t have an account?',
            'label' => 'Sign up for an account',
        ],

        'request_password_reset' => [
            'label' => 'Forgot password?',
        ],

    ],

    'form' => [

        'email' => [
            'label' => 'Email address',
        ],

        'password' => [
            'label' => 'Password',
        ],

        'remember' => [
            'label' => 'Remember me',
        ],

        'actions' => [

            'authenticate' => [
                'label' => 'Sign in',
            ],

        ],

    ],

    'messages' => [

        'failed' => 'These credentials do not match our records.',

    ],

    'notifications' => [

        'throttled' => [
            'title' => 'Too many login attempts',
            'body' => 'Please try again in :seconds seconds.',
        ],

    ],

];
