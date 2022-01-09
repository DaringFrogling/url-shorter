<?php

namespace App\Entity\Stats;

use App\Entity\IdentifierInterface;
use App\Entity\IntIdentifier;
use App\Entity\Link\Link;
use App\Entity\Link\LinkInterface;
use App\Repository\Stats\StatsRepository;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embedded;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[
    Table(name: 'link_stats'),
    Entity(repositoryClass: StatsRepository::class)
]
class Stats implements StatsInterface
{
    #[Embedded(class: IntIdentifier::class, columnPrefix: false)]
    private IdentifierInterface $identifier;

    /**
     * Stats constructor.
     *
     * @param LinkInterface $link
     * @param Visitor $visitor
     * @param DateTimeInterface $visitedAt
     */
    public function __construct(
        #[
            ManyToOne(targetEntity: Link::class),
            JoinColumn(name: 'link_id', referencedColumnName: 'id')
        ]
        private LinkInterface $link,

        #[Embedded(class: Visitor::class, columnPrefix: false)]
        private Visitor $visitor,

        #[Column(name: 'visited_at', type: 'datetime')]
        private DateTimeInterface $visitedAt = new DateTimeImmutable('now'),
    ) {
    }

    public function getIdentifier(): IdentifierInterface
    {
        return $this->identifier;
    }

    public function getLink(): LinkInterface
    {
        return $this->link;
    }

    public function getVisitor(): Visitor
    {
        return $this->visitor;
    }

    public function getVisitedAt(): DateTimeInterface
    {
        return $this->visitedAt;
    }
}