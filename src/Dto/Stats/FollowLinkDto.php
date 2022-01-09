<?php

namespace App\Dto\Stats;

use App\Dto\DtoInterface;
use App\Entity\Link\LinkInterface;

class FollowLinkDto implements DtoInterface
{
    public function __construct(
        public readonly LinkInterface $shortenedUriIdentifier,
        public readonly string $ipAddress,
        public readonly array $userAgent
    ) {
    }
}