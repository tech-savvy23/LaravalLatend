<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class UnAuthorized extends Exception
{
    public function render()
    {
        return response()->json([
            'status' => 403, //Unauthorized
            'response' => 'Error',
            'data' => [
                'error' => [
                    'Unauthorized',
                ]
            ],
        ], Response::HTTP_FORBIDDEN);

    }
}
