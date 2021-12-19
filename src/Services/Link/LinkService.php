<?php

namespace App\Services\Link;

use App\ConstantBag\ExceptionMessages;
use App\Dto\Link\LinkCreateDto;
use App\Dto\Link\LinkUpdateDto;
use App\Entity\IdentifierInterface;
use App\Entity\Link\Link;
use App\Entity\Link\LinkInterface;
use App\Repository\RepositoryInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 *
 */
class LinkService implements LinkServiceInterface
{
    public function __construct(
        private RepositoryInterface $linkRepository
    ) {
    }

    public function getByIdentifier(IdentifierInterface $identifier): LinkInterface
    {
        $link = $this->linkRepository->find($identifier->getValue());

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

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function delete(IdentifierInterface $identifier): void
    {
        $link = $this->getByIdentifier($identifier);

        $this->linkRepository->delete($link->getId());
        $this->linkRepository->save($link);
    }
}