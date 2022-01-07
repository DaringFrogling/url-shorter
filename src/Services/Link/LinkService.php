<?php

namespace App\Services\Link;

use App\ConstantBag\ExceptionMessages;
use App\Dto\Link\LinkCreateDto;
use App\Dto\Link\LinkUpdateDto;
use App\Entity\IdentifierInterface;
use App\Entity\Link\Link;
use App\Entity\Link\LinkInterface;
use App\Repository\Link\LinkRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
        $link = $this->linkRepository->find($identifier);

        if (!$link) {
            throw new NotFoundHttpException(ExceptionMessages::LINK_NOT_FOUND);
        }

        return $link;
    }

    public function update(LinkUpdateDto $linkUpdateDto): void
    {
        $link = $this->getByIdentifier($linkUpdateDto->id);

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

    public function delete(IdentifierInterface $identifier): void
    {
        $link = $this->getByIdentifier($identifier);

        $this->linkRepository->delete($link);
        $this->linkRepository->save($link);
    }
}