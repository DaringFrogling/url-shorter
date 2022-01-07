<?php

namespace App\Repository\Link;

use App\Entity\EntityInterface;
use App\Entity\IdentifierInterface;
use App\Entity\Link\Link;
use App\Entity\Link\LinkInterface;
use Doctrine\DBAL\ParameterType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;

class LinkRepository implements LinkRepositoryInterface
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
    public function find(IdentifierInterface $identifier): ?EntityInterface
    {
        return $this->createQueryBuilder()
            ->andWhere('l.identifier.id = :identifier')
            ->setParameter('identifier', $identifier->getValue())
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @inheritDoc
     */
    public function findAll(): array
    {
        return $this->createQueryBuilder()
            ->getQuery()
            ->getResult();
    }

    /**
     * @inheritDoc
     */
    public function findBy(array $criteria, int $limit = 15): array
    {
        $qb = $this->createQueryBuilder();

        if ($criteria['title']) {
            $qb->andWhere('l.title like :title')
                ->setParameter('title', '%'.mb_strtolower($criteria['title']).'%', ParameterType::STRING);
        }

        if (!empty($criteria['tags'])) {
            $tags = $criteria['tags'];
            foreach ($tags as $tag) {
                $qb->andWhere('l.tags like :tag')
                    ->setParameter('tag', '%'.mb_strtolower($tag).'%');
            }
        }

        return $qb->setMaxResults($limit)
            ->orderBy('l.identifier.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @inheritDoc
     */
    public function save(LinkInterface $link): void
    {
        $this->em->persist($link);
        $this->em->flush($link);
    }

    /**
     * @inheritDoc
     */
    public function delete(LinkInterface $link): void
    {
        $this->em->remove($link);
    }

    /**
     * Created QueryBuilder instance.
     *
     * @return QueryBuilder
     */
    private function createQueryBuilder(): QueryBuilder
    {
        return $this->em->createQueryBuilder()
            ->select('l')
            ->from(Link::class, 'l');
    }
}
