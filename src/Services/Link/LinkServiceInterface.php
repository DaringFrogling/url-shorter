<?php

namespace App\Services\Link;

use App\Dto\Link\LinkCreateDto;
use App\Dto\Link\LinkUpdateDto;
use App\Entity\IdentifierInterface;
use App\Entity\Link\LinkInterface;
use Doctrine\ORM\EntityNotFoundException;

/**
 *
 */
interface LinkServiceInterface
{
    /**
     * Gets Link by its identifier.
     *
     * @param IdentifierInterface $identifier
     * @return LinkInterface
     * @throws EntityNotFoundException
     */
    public function getByIdentifier(IdentifierInterface $identifier): LinkInterface;

    /**
     * Gets Link by its shortened URI identifier.
     *
     * @param IdentifierInterface $identifier
     * @return LinkInterface
     * @throws EntityNotFoundException
     */
    public function getByShortenedUri(IdentifierInterface $identifier): LinkInterface;

    /**
     * Create Link.
     *
     * @param LinkCreateDto $linkCreateDto
     */
    public function create(LinkCreateDto $linkCreateDto): void;

    /**
     * Updates Link.
     *
     * @param LinkUpdateDto $linkUpdateDto
     */
    public function update(LinkUpdateDto $linkUpdateDto): void;

    /**
     * Deletes Link.
     *
     * @param IdentifierInterface $identifier
     */
    public function delete(IdentifierInterface $identifier): void;
}