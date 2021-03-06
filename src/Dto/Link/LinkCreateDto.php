<?php

namespace App\Dto\Link;

use App\Dto\DtoInterface;

/**
 * 
 */
class LinkCreateDto implements DtoInterface
{
    public function __construct(
        public readonly mixed $originalUrl,
        public readonly mixed $title,
        public readonly mixed $tags,
    ) {
    }
}