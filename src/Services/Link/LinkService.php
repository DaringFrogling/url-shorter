<?php

namespace App\Services\Link;

use App\ConstantBag\ExceptionMessages;
use App\Dto\Link\LinkCreateDto;
use App\Dto\Link\LinkUpdateDto;
use App\Entity\IdentifierInterface;
use App\Entity\Link\Link;
use App\Entity\Link\LinkInterface;
use App\Repository\Link\LinkRepositoryInterface;
use Doctrine\ORM\EntityNotFoundException;

/**
 *
 */
class LinkService implements LinkServiceInterface
{
    public function __construct(
        private LinkRepositoryInterface $linkRepository
    ) {
    }

    public function getByIdentifier(IdentifierInterface $identifier): LinkInterface
    {
        $link = $this->linkRepository->findByIdentifier($identifier);

        if (!$link) {
            throw new EntityNotFoundException(ExceptionMessages::LINK_NOT_FOUND);
        }

        return $link;
    }

    public function getByShortenedUri(IdentifierInterface $identifier): LinkInterface
    {
        $link = $this->linkRepository->findByShortenedUri($identifier);

        if (!$link) {
            throw new EntityNotFoundException(ExceptionMessages::LINK_NOT_FOUND);
        }

        return $link;
    }

    /**
     * @throws EntityNotFoundException
     */
    public function update(LinkUpdateDto $linkUpdateDto): void
    {
        $link = $this->getByShortenedUri($linkUpdateDto->shortenedUriIdentifier);

        $link->update(
            $linkUpdateDto->originalUrl,
            $linkUpdateDto->title,
            $linkUpdateDto->tags
        );

        $this->linkRepository->save($link);
    }

    public function create(LinkCreateDto $linkCreateDto): void
    {
        $link = new Link(
            $linkCreateDto->originalUrl,
            $linkCreateDto->title,
            $linkCreateDto->tags
        );

        $this->linkRepository->save($link);
    }

    /**
     * @throws EntityNotFoundException
     */
    public function delete(IdentifierInterface $identifier): void
    {
        $link = $this->getByShortenedUri($identifier);

        $this->linkRepository->delete($link);
        $this->linkRepository->save($link);
    }
}