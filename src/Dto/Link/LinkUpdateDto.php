<?php

namespace App\Dto\Link;

use App\Dto\DtoInterface;

/**
 * 
 */
class LinkUpdateDto implements DtoInterface
{
    public function __construct(
        public readonly mixed $shortenedUriIdentifier,
        public readonly mixed $originalUrl,
        public readonly mixed $title,
        public readonly array $tags,
    ) {
    }
}