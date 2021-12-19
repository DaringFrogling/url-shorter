<?php

namespace App\Repository\Stats;

use App\Entity\Stats\Stats;
use App\Repository\BaseRepository;
use Doctrine\Persistence\ManagerRegistry;

class StatsRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Stats::class);
    }
}