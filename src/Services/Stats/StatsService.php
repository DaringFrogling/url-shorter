<?php

namespace App\Services\Stats;

use App\ConstantBag\ExceptionMessages;
use App\Dto\Stats\FollowLinkDto;
use App\Entity\IdentifierInterface;
use App\Entity\Stats\Stats;
use App\Entity\Stats\Visitor;
use App\Repository\Stats\StatsRepositoryInterface;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\NoResultException;
use JetBrains\PhpStorm\ArrayShape;

class StatsService implements StatsServiceInterface
{
    public function __construct(
        private StatsRepositoryInterface $statsRepository
    ) {
    }

    public function followLink(FollowLinkDto $followLinkDto): void
    {
        $stats = new Stats(
            $followLinkDto->shortenedUriIdentifier,
            new Visitor(
                $followLinkDto->ipAddress,
                $followLinkDto->userAgent,
            ),
        );

        $this->statsRepository->save($stats);
    }

    /**
     * @inheritDoc
     *
     * @throws EntityNotFoundException
     */
    #[ArrayShape(['total_views' => 'array', 'unique_views' => 'array'])]
    public function countTotalAndUniqueVisits(IdentifierInterface $identifier = null): array
    {
        try {
            $total = $this->statsRepository->countTotal($identifier ?? null);
            $unique = $this->statsRepository->countUnique($identifier ?? null);
        } catch (NoResultException) {
            throw new EntityNotFoundException(ExceptionMessages::NO_STATS_FOUND);
        }

        return [$total, $unique];
    }
}