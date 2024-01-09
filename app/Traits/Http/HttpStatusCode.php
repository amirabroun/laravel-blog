<?php

namespace App\Traits\Http;

trait HttpStatusCode
{
    const HTTP_STATUS_CODE = [
        'success' => 200,
        'bad_request' => 400,
        'unauthorized' => 401,
        'forbidden' => 403,
        'not_found' => 404,
        'unprocessable_entity' => 422,
        'server_error' => 500,
    ];
}
