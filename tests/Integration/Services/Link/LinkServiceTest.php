<?php

namespace App\Tests\Integration\Services\Link;

use App\ConstantBag\ExceptionMessages;
use App\Dto\Link\LinkCreateDto;
use App\Dto\Link\LinkUpdateDto;
use App\Entity\Link\Link;
use App\Entity\Link\LinkInterface;
use App\Entity\LinkIdentifier;
use App\Repository\Link\LinkRepository;
use App\Services\Link\LinkService;
use App\Services\Link\LinkServiceInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 *
 */
class LinkServiceTest extends WebTestCase
{
    private LinkServiceInterface $linkService;

    private LinkRepository $linkRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->linkService = self::getContainer()->get(LinkService::class);
        $this->linkRepository = self::getContainer()->get(LinkRepository::class);
    }

    public function testCreate(): void
    {
        $link = $this->createAndReturnCreatedLink();

        $this->assertNotNull($link);
        $this->assertInstanceOf(LinkInterface::class, $link);
    }

    public function testUpdate(): void
    {
        $link = $this->createAndReturnCreatedLink();

        $updateDto = new LinkUpdateDto(new LinkIdentifier($link->getId()), 'https://new-link.co', 'new title', ['new-tag']);
        $this->linkService->update($updateDto);
        $updatedLink = $this->linkService->getByIdentifier(new LinkIdentifier($link->getId()));

        $this->assertInstanceOf(LinkInterface::class, $updatedLink);
        $this->assertEquals($updatedLink->getId(), $link->getId());
        $this->assertEquals('https://new-link.co', $updatedLink->getOriginalUrl());
        $this->assertEquals('new title', $updatedLink->getTitle());
        $this->assertEquals(['new-tag'], $updatedLink->getTags());
    }

    public function testDelete(): void
    {
        $link = $this->createAndReturnCreatedLink();
        $this->linkService->delete(new LinkIdentifier($link->getId()));

        $this->expectException(NotFoundHttpException::class);
        $this->expectExceptionMessage(ExceptionMessages::LINK_NOT_FOUND);

        $this->linkService->getByIdentifier(new LinkIdentifier($link->getId()));
    }

    public function testGetByIdentifier(): void
    {
        $link = $this->createAndReturnCreatedLink();

        $foundedLink = $this->linkService->getByIdentifier(new LinkIdentifier($link->getId()));

        $this->assertInstanceOf(LinkInterface::class, $foundedLink);
    }

    public function testGetByIdentifierWithException(): void
    {
        $this->expectException(NotFoundHttpException::class);
        $this->expectExceptionMessage(ExceptionMessages::LINK_NOT_FOUND);

        $this->linkService->getByIdentifier(new LinkIdentifier('123QWDasAa'));
    }

    private function createAndReturnCreatedLink(): LinkInterface
    {
        $createDto = new LinkCreateDto('https://link.co', 'My link', ['The tag']);
        $this->linkService->create($createDto);

        $links = $this->linkRepository->findBy(
            Link::class,
            ['title' => 'My link'],
            null,
            1
        );

        return $links[0];
    }
}
