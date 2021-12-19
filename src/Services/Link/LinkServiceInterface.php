<?php

namespace App\Services\Link;

use App\Dto\Link\LinkCreateDto;
use App\Dto\Link\LinkUpdateDto;
use App\Entity\IdentifierInterface;
use App\Entity\Link\LinkInterface;

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
     */
    public function getByIdentifier(IdentifierInterface $identifier): LinkInterface;

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