<?php

return [

    'title' => 'Terima kasih telah mendaftar! Sebelum memulai, bisakah Anda memverifikasi alamat email Anda dengan mengklik tautan yang baru saja kami kirimkan? Jika Anda tidak menerima email tersebut, kami akan dengan senang hati mengirimkan email baru',

    'heading' => 'Verifikasi alamat email Anda',

    'actions' => [

        'resend_notification' => [
            'label' => 'Kirim ulang email verifikasi',
        ],

        'logout' => [
            'label' => 'Keluar',
        ],

    ],

    'messages' => [
        'notification_not_received' => 'Belum menerima email?',
        'notification_sent' => 'Tautan verifikasi baru telah dikirim ke alamat email :email.',
    ],

    'notifications' => [

        'notification_resent' => [
            'title' => 'Email telah dikirim ulang.',
        ],

        'notification_resend_throttled' => [
            'title' => 'Terlalu banyak permintaan',
            'body' => 'Silakan coba lagi dalam :seconds detik.',
        ],

    ],

];
