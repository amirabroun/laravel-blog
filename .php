<?php
return [

    'update_id' => 480365779,
    'message' => [
        'message_id' => 447,
        'from' => [
            'id' => 358845666,
            'is_bot' => false,
            'first_name' => 'Amir',
            'last_name' => 'Abroun',
            'username' => 'Amir_Abroun',
            'language_code' => 'en',
        ],
        'chat' => [
            'id' => 358845666,
            'first_name' => 'Amir',
            'last_name' => 'Abroun',
            'username' => 'Amir_Abroun',
            'type' => 'private',
        ],
        'date' => 1703160106,
        'text' => '/help',
        'entities' => [
            [
                'offset' => 0,
                'length' => 5,
                'type' => 'bot_command',
            ],
        ],
    ],
];

[
    'update_id' => 480365784,
    'callback_query' => [
        'id' => '1541230401797510449',
        'from' => [
            'id' => 358845666,
            'is_bot' => false,
            'first_name' => 'Amir',
            'last_name' => 'Abroun',
            'username' => 'Amir_Abroun',
            'language_code' => 'en',
        ],
        'message' => [
            'message_id' => 452,
            'from' =>
            [
                'id' => 6401226118,
                'is_bot' => true,
                'first_name' => 'e-blog',
                'username' => 'e_blogbot',
            ],
            'chat' =>
            [
                'id' => 358845666,
                'first_name' => 'Amir',
                'last_name' => 'Abroun',
                'username' => 'Amir_Abroun',
                'type' => 'private',
            ],
            'date' => 1703161468,
            'reply_to_message' =>
            [
                'message_id' => 447,
                'from' =>
                [
                    'id' => 358845666,
                    'is_bot' => false,
                    'first_name' => 'Amir',
                    'last_name' => 'Abroun',
                    'username' => 'Amir_Abroun',
                    'language_code' => 'en',
                ],
                'chat' =>
                [
                    'id' => 358845666,
                    'first_name' => 'Amir',
                    'last_name' => 'Abroun',
                    'username' => 'Amir_Abroun',
                    'type' => 'private',
                ],
                'date' => 1703160106,
                'text' => '/help',
                'entities' =>
                [
                    [
                        'offset' => 0,
                        'length' => 5,
                        'type' => 'bot_command',
                    ],
                ],
            ],
            'text' => 'hi',
            'reply_markup' => [
                'inline_keyboard' => [
                    [
                        [
                            'text' => 'ok',
                            'callback_data' => '/start',
                        ],
                        [
                            'text' => 'cancel',
                            'callback_data' => '/end',
                        ],
                    ],
                ],
            ],
        ],
        'chat_instance' => '8008436492245263306',
        'data' => '/start',
    ],
];
