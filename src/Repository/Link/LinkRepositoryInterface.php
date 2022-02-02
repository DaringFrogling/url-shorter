<?php

namespace App\Repository\Link;

use App\Entity\IdentifierInterface;
use App\Entity\Link\LinkInterface;

/**
 *
 */
interface LinkRepositoryInterface
{
    /**
     * Finds entity by identifier.
     *
     * @param IdentifierInterface $identifier
     * @return LinkInterface|null
     */
    public function findByIdentifier(IdentifierInterface $identifier): ?LinkInterface;

    /**
     * Finds by shortened uri identifier.
     *
     * @param IdentifierInterface $identifier
     * @return LinkInterface|null
     */
    public function findByShortenedUri(IdentifierInterface $identifier): ?LinkInterface;

    /**
     * Finds all entities.
     *
     * @return array
     */
    public function findAll(): array;

    /**
     * Finds entities by criteria.
     *
     * @param mixed $criteria
     * @param int $limit
     *
     * @return array
     */
    public function findBy(array $criteria, int $limit = 15): array;

    /**
     * Saves entity.
     *
     * @param LinkInterface $link
     */
    public function save(LinkInterface $link): void;

    /**
     * Deletes entity.
     *
     * @param LinkInterface $link
     */
    public function delete(LinkInterface $link): void;
}