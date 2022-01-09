<?php

namespace App\Repository\Stats;

use App\Entity\IdentifierInterface;
use App\Entity\Stats\StatsInterface;
use Doctrine\ORM\NoResultException;

interface StatsRepositoryInterface
{
    /**
     * Counts the total visits.
     *
     * @param IdentifierInterface $identifier
     * @return array
     * @throws NoResultException
     */
    public function countTotal(IdentifierInterface $identifier): array;

    /**
     * Counts unique visits.
     *
     * @param IdentifierInterface $identifier
     * @return array
     * @throws NoResultException
     */
    public function countUnique(IdentifierInterface $identifier): array;

    /**
     * Saves the entity.
     *
     * @param StatsInterface $stats
     */
    public function save(StatsInterface $stats): void;
}