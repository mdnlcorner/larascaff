<?php

return [

    'title' => 'Forgot password?',

    'heading' => 'Enter your email and we\'ll send you instructions to reset your password',

    'actions' => [

        'login' => [
            'label' => 'Back to login',
        ],

    ],

    'form' => [

        'email' => [
            'label' => 'Email address',
        ],

        'actions' => [

            'request' => [
                'label' => 'Send email',
            ],

        ],

    ],

    'notifications' => [

        'throttled' => [
            'title' => 'Too many requests',
            'body' => 'Please try again in :seconds seconds.',
        ],

    ],

];
