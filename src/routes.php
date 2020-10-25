<?php

return [
    '~session_reset/$~' => [Controllers\RegController::class, 'sessionUnset'],
    '~main_paige$~' => [Controllers\MainController::class, 'mainPage'],
    '~(^$)|(main/(.*))~' => [Controllers\MainController::class, 'main'],
    '~register_user_post$~' => [Controllers\RegController::class, 'register'],
    '~reg/(.*)$~' => [Controllers\RegController::class, 'main'],
    '~^auth/(.*)$~' => [Controllers\AuthController::class, 'main']
];