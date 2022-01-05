<?php

namespace App\Repository;

use App\Entity\EntityInterface;
use App\Entity\IdentifierInterface;

/**
 *
 */
interface RepositoryInterface
{
    /**
     * Finds entity by identifier.
     *
     * @param string $entityFQCN
     * @param IdentifierInterface $identifier
     * @return EntityInterface|null
     */
    public function find(string $entityFQCN, IdentifierInterface $identifier): ?EntityInterface;

    /**
     * Finds all entities.
     *
     * @param string $entityFQCN
     * @return array
     */
    public function findAll(string $entityFQCN): array;

    /**
     * Finds entities by criteria.
     *
     * @param string $entityFQCN
     * @param mixed $criteria
     * @param array|null $orderBy
     * @param int|null $limit
     *
     * @return array
     */
    public function findBy(string $entityFQCN, mixed $criteria, ?array $orderBy, ?int $limit): array;

    /**
     * Saves entity.
     *
     * @param EntityInterface $entity
     */
    public function save(EntityInterface $entity): void;

    /**
     * Deletes entity.
     *
     * @param EntityInterface $entity
     */
    public function delete(EntityInterface $entity): void;
}