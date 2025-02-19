<?php

namespace App\ApiDoc\Attributes;

use Attribute;
use OpenApi\Attributes\Attachable;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\MediaType;
use OpenApi\Attributes\Response;
use OpenApi\Attributes\XmlContent;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
final class OkResponse extends Response
{
    public function __construct(
        null|object|string $ref = null,
        null|int|string $response = 200,
        ?string $description = 'Success',
        ?array $headers = null,
        null|array|Attachable|JsonContent|MediaType|XmlContent $content = null,
        ?array $links = null,
        ?array $x = null,
        ?array $attachables = null
    ) {
        parent::__construct($ref, $response, $description, $headers, $content, $links, $x, $attachables);
    }
}
