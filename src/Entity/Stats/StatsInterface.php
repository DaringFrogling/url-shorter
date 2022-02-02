<?php

namespace App\Entity\Stats;

use App\Entity\EntityInterface;
use App\Entity\Link\LinkInterface;
use DateTimeInterface;

/**
 *
 */
interface StatsInterface extends EntityInterface
{
    public function getLink(): LinkInterface;

    public function getVisitor(): Visitor;

    public function getVisitedAt(): DateTimeInterface;
}