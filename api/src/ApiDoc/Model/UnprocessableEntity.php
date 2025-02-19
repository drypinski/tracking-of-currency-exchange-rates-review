<?php

namespace App\ApiDoc\Model;

use OpenApi\Attributes as OA;

final readonly class UnprocessableEntity
{
    public function __construct(
        #[OA\Property(
            required: ['properties'],
            properties: [
                new OA\Property(
                    property: 'properties',
                    properties: [
                        new OA\Property(
                            property: 'errorPropertyName',
                            type: 'array',
                            items: new OA\Items(type: 'string'),
                            example: ['Error message']
                        ),
                    ],
                    type: 'object',
                ),
            ],
            type: 'object'
        )]
        public array $error
    ) {}
}
