<?php

return [
    '~session_reset$~' => [Controllers\MainController::class, 'sessionUnset'],
    '~main_paige$~' => [Controllers\MainController::class, 'mainPage'],
    '~(^$)|(main/(.*))~' => [Controllers\MainController::class, 'main'],
    '~^reg/$~' => [Controllers\RegController::class, 'main'],
    '~register_user_post$~' => [Controllers\RegController::class, 'register'],
    '~^auth/(.*)$~' => [Controllers\AuthController::class, 'main'],

];