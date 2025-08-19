<?php

return [
    'default_filesystem_disk' => env('DEFAULT_FILESYSTEM_DISK', 'public'),

    'default_temp_upload_disk' => env('DEFAULT_TEMP_UPLOAD_DISK', 'local'),

    'keep_temp_upload_in' => env('KEEP_TEMP_UPLOAD_IN', 180), // in minutes
];
