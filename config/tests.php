<?php
return [
    'hide_expired_tests' => false,
    'seconds_in_minute' => env('SECONDS_IN_MINUTE', 60),
    'K_DISPLAY_TEST_DESCRIPTION' => true,
    'display_test_description' => env('DISPLAY_TEST_DESCRIPTION', true),
    'enable_virtual_keyboard' => env('ENABLE_VIRTUAL_KEYBOARD', true),
    'answer_textarea_cols' => env('ANSWER_TEXTAREA_COLS', 80),
    'answer_textarea_rows' => env('ANSWER_TEXTAREA_ROWS', 10),
];
