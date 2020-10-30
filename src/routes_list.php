<?php

return [
    '~session_reset/$~' => [Controllers\RegController::class, 'sessionUnsetAction'],
    '~main_paige$~' => [Controllers\MainController::class, 'mainPage'],
    '~(^$)|(main/(.*))~' => [Controllers\MainController::class, 'main'],
    '~register_user_post$~' => [Controllers\RegController::class, 'register'],
    '~reg/$~' => [Controllers\RegController::class, 'mainAction'],
    '~^auth/$~' => [Controllers\AuthController::class, 'main'],
    '~^captcha/(.*)$~' => [Controllers\AuthController::class, 'captcha']
];
