<?php

namespace App\Repository;

use App\Entity\EntityInterface;
use App\Entity\IdentifierInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 *
 */
abstract class BaseRepository implements RepositoryInterface
{
    public function __construct(
        protected EntityManagerInterface $em,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function find(string $entityFQCN, IdentifierInterface $identifier): ?EntityInterface
    {
        return $this->em->createQueryBuilder()
            ->select('e')
            ->from($entityFQCN, 'e')
            ->andWhere('e.id = :identifier')
            ->setParameter('identifier', $identifier->getValue())
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @inheritDoc
     */
    public function findAll(string $entityFQCN): array
    {
        return $this->em->createQueryBuilder()
            ->select('e')
            ->from($entityFQCN, 'e')
            ->getQuery()
            ->getResult();
    }

    /**
     * @inheritDoc
     */
    public function save(EntityInterface $entity): void
    {
        $this->em->persist($entity);
        $this->em->flush($entity);
    }

    /**
     * @inheritDoc
     */
    public function delete(EntityInterface $entity): void
    {
        $this->em->remove($entity);
    }
}