<?php

namespace App\Repository;

use App\Entity\EntityInterface;

/**
 *
 */
interface RepositoryInterface
{
    /**
     * Saves entity.
     *
     * @param EntityInterface $entity
     */
    public function save(EntityInterface $entity): void;
}