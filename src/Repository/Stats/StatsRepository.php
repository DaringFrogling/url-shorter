<?php

namespace App\Repository\Stats;

use App\Entity\IdentifierInterface;
use App\Entity\Stats\Stats;
use App\Entity\Stats\StatsInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;

class StatsRepository implements StatsRepositoryInterface
{
    public function __construct(
        protected EntityManagerInterface $em,
    ) {
    }

    /**
     * @inheritDoc
     *
     * @throws NonUniqueResultException
     */
    public function countTotal(IdentifierInterface $identifier = null): array
    {
        $qb = $this->em->createQueryBuilder()
            ->from(Stats::class, 's')
            ->groupBy('s.link')
            ->select('IDENTITY(s.link) as link_id, COUNT(s.link) as total_views');

        if ($identifier) {
            $qb->andWhere('s.link = :identifier')
                ->setParameter('identifier', $identifier->getValue());

            return $qb->getQuery()->getSingleResult();
        }

        return $qb->getQuery()->getArrayResult();
    }

    /**
     * @inheritDoc
     */
    public function countUnique(IdentifierInterface $identifier = null): array
    {
        $qb = $this->em->createQueryBuilder()
            ->from(Stats::class, 's')
            ->groupBy('s.link, s.visitor.ip, s.visitor.userAgent')
            ->select('IDENTITY(s.link) as link_id');

        if ($identifier) {
            $qb->andWhere('s.link = :identifier')
                ->setParameter('identifier', $identifier->getValue());

            $result = $qb->getQuery()->getArrayResult();

            return ['link_id' => $identifier->getValue(), 'unique_views' => count($result)];
        }

        $result = $qb->getQuery()->getArrayResult();
        $aggregateForSameLinksCallback = function (array $initial, array $element) {
            if (!empty($initial)) {
                $key = $this->getFirstLevelKeyForValue($initial, $element);
                if (in_array($element['link_id'], $initial[$key], true)) {
                    $initial[$key]['unique_views']++;
                } else {
                    $key = max(array_keys($initial));
                    $key++;
                    $initial[$key]['link_id'] = $element['link_id'];
                    $initial[$key]['unique_views'] = 1;
                }
            } else {
                $initial[0]['link_id'] = $element['link_id'];
                $initial[0]['unique_views'] = 1;
            }

            return $initial;
        };

        return array_reduce($result, $aggregateForSameLinksCallback, []);
    }

    /**
     * @inheritDoc
     */
    public function save(StatsInterface $stats): void
    {
        $this->em->persist($stats);
        $this->em->flush($stats);
    }

    /**
     * Gets first level key of the multidimensional array for searched value.
     *
     * @param array $initial
     * @param array $element
     * @return int
     */
    private function getFirstLevelKeyForValue(array $initial, array $element): int
    {
        foreach ($initial as $k => $item) {
            if ($item['link_id'] === $element['link_id']) {
                $key = $k;
                break;
            }
        }
        if (!isset($key)) {
            $keys = array_keys($initial);
            $key = $keys ? min($keys) : 0;
        }

        return $key;
    }
}