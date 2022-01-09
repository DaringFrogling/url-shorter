<?php

namespace App\Services\Stats;

use App\Dto\Stats\FollowLinkDto;
use App\Entity\IdentifierInterface;

/**
 *
 */
interface StatsServiceInterface
{
    /**
     * Creates a link following entry.
     *
     * @param FollowLinkDto $followLinkDto
     * @return void
     */
    public function followLink(FollowLinkDto $followLinkDto): void;

    /**
     * Counts the total and unique number of clicks on the link.
     *
     * @param IdentifierInterface $identifier
     * @return array
     */
    public function countTotalAndUniqueVisits(IdentifierInterface $identifier): array;
}