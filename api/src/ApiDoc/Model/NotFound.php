<?php

namespace App\ApiDoc\Model;

use OpenApi\Attributes as OA;

final readonly class NotFound
{
    public function __construct(
        #[OA\Property(
            required: ['message'],
            properties: [
                new OA\Property(
                    property: 'message',
                    type: 'string',
                    example: 'Not found message.'
                ),
            ],
            type: 'object'
        )]
        public array $error,
    ) {}
}
