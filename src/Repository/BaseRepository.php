<?php

namespace App\Repository;

use App\Entity\EntityInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 *
 */
abstract class BaseRepository extends ServiceEntityRepository implements RepositoryInterface
{
    public function __construct(ManagerRegistry $registry, string $entityClass)
    {
        parent::__construct($registry, $entityClass);
    }

    /**
     * @inheritDoc
     *
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function save(EntityInterface $entity): void
    {
        $this->_em->persist($entity);
        $this->_em->flush($entity);
    }
}