<?php

namespace App\Dto\Link;

use App\Dto\DtoInterface;
use App\Entity\IdentifierInterface;

/**
 * 
 */
class LinkUpdateDto implements DtoInterface
{
    public function __construct(
        public readonly IdentifierInterface $shortenedUriIdentifier,
        public readonly mixed $originalUrl,
        public readonly mixed $title,
        public readonly array $tags,
    ) {
    }
}