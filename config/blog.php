<?php

return [

    /*
    |--------------------------------------------------------------------------
    | User Base
    |--------------------------------------------------------------------------
    |
    | Check the users can register and have account
    |
    */

    'can_users_register' => env('CAN_USERS_REGISTER', false),

    'admin_keys' => env('SECRET_LOGIN', 'amirabroun'),

];
