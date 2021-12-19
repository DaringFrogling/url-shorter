<?php

namespace App\Entity\Stats;

use App\Entity\IdentifierInterface;
use App\Repository\Stats\StatsRepository;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

#[
    ORM\Entity(repositoryClass: StatsRepository::class),
    ORM\Table(name: 'statistic')
]
class Stats implements StatsInterface
{
    #[
        ORM\Id,
        ORM\Column,
        ORM\GeneratedValue(strategy: 'AUTO'),
    ]
    private int $id;

    /**
     * Stats constructor.
     *
     * @param IdentifierInterface $linkIdentifier
     * @param int $totalViews
     * @param int $uniqueViews
     * @param Visitor $visitor
     * @param DateTimeInterface|null $visitedAt
     */
    public function __construct(
        #[ORM\Column]
        private IdentifierInterface $linkIdentifier,

        #[ORM\Column]
        private int $totalViews,

        #[ORM\Column]
        private int $uniqueViews,

        #[ORM\Column]
        private Visitor $visitor,

        #[ORM\Column]
        private ?DateTimeInterface $visitedAt = new DateTimeImmutable('now'),
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return IdentifierInterface
     */
    public function getLinkIdentifier(): IdentifierInterface
    {
        return $this->linkIdentifier;
    }

    /**
     * @return int
     */
    public function getTotalViews(): int
    {
        return $this->totalViews;
    }

    /**
     * @return int
     */
    public function getUniqueViews(): int
    {
        return $this->uniqueViews;
    }

    /**
     * @return Visitor
     */
    public function getVisitor(): Visitor
    {
        return $this->visitor;
    }

    /**
     * @return DateTimeImmutable|DateTimeInterface|null
     */
    public function getVisitedAt(): DateTimeImmutable|DateTimeInterface|null
    {
        return $this->visitedAt;
    }
}