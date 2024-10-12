<?php

return [
    'pagination' => [
        'default_all_limit' => -1,
        'default_limit' => 10,
        'default_offset' => 0,
    ],
    'keyword' => [
        'key' => 'key',
        'value' => 'value',
        'others' => 'OTHERS',
    ],
    'log_file_name' => [
        'error_log' => 'error_log',
        'success_log' => 'success_log',
    ],
    'model_paths' => [
        app_path() . "/Models",
        base_path() . "/Modules",
    ],
    'safe_special_chars' => " $-_.+!*%#'\"\\(){}[];/?:@=&<>,\n\r\t\b\f\a\v\0",
    'message' => [
        'not_found' => 'Record Not Found',
        'bad_request' => 'Bad Request',
        'reg_success' => 'Registration Successful.',
    ],
    'holidays' => ['Friday', 'Saturday'],
];


