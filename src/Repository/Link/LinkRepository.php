<?php

namespace App\Repository\Link;

use App\Entity\Link\Link;
use App\Repository\BaseRepository;
use Doctrine\DBAL\ParameterType;

/**
 *
 */
class LinkRepository extends BaseRepository
{
    /**
     * @inheritDoc
     */
    public function findBy(string $entityFQCN, mixed $criteria, ?array $orderBy, ?int $limit): array
    {
        $qb = $this->em->createQueryBuilder()
            ->select('l')
            ->from(Link::class, 'l');

        if (!empty($criteria['title'])) {
            $qb->andWhere('l.title = :title')
                ->setParameter('title', $criteria['title'], ParameterType::STRING);
        }

        if (!empty($criteria['tags'])) {
            $tags = $criteria['tags'];
            foreach ($tags as $tag) {
                $qb->andWhere('l.tags like :tag')
                    ->setParameter('tag', '%' . $tag . '%');
            }
        }

        return $qb->setMaxResults($limit ?? 15)
            ->getQuery()
            ->getResult();
    }
}
