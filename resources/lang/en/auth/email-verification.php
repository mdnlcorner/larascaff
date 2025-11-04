<?php

return [

    'title' => 'Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.',

    'heading' => 'Verify your email address',

    'actions' => [

        'resend_notification' => [
            'label' => 'Resend Verification Email',
        ],

        'logout' => [
            'label' => 'Logout',
        ],

    ],

    'messages' => [
        'notification_not_received' => 'Not received the email we sent?',
        'notification_sent' => 'A new verification link has been sent to your email :email.',
    ],

    'notifications' => [

        'notification_resent' => [
            'title' => 'We\'ve resent the email.',
        ],

        'notification_resend_throttled' => [
            'title' => 'Too many resend attempts',
            'body' => 'Please try again in :seconds seconds.',
        ],

    ],

];
