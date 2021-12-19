<?php

namespace App\Tests\Integration\Services\Link;

use App\ConstantBag\ExceptionMessages;
use App\Dto\Link\LinkCreateDto;
use App\Dto\Link\LinkUpdateDto;
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

        $this->linkService = $this->getContainer()->get(LinkService::class);
        $this->linkRepository = $this->getContainer()->get(LinkRepository::class);
    }

    public function testCreate()
    {
        $link = $this->createAndReturnCreatedLink();

        $this->assertNotNull($link);
        $this->assertInstanceOf(LinkInterface::class, $link);
    }

    public function testUpdate()
    {
        $link = $this->createAndReturnCreatedLink();

        $updateDto = new LinkUpdateDto($link->getId(), 'https://new-link.co', 'new title', []);
        $this->linkService->update($updateDto);
        $updatedLink = $this->linkService->getByIdentifier($link->getId());

        $this->assertInstanceOf(LinkInterface::class, $updatedLink);
        $this->assertTrue($updatedLink->getId()->equals($link->getId()));
        $this->assertEquals('https://new-link.co', $updatedLink->getOriginalUrl());
        $this->assertEquals('new title', $updatedLink->getTitle());
        $this->assertEmpty($updatedLink->getTags());
    }

    public function testDelete()
    {
        $link = $this->createAndReturnCreatedLink();
        $this->linkService->delete($link->getId());

        $this->expectException(NotFoundHttpException::class);
        $this->expectExceptionMessage(ExceptionMessages::LINK_NOT_FOUND);

        $this->linkService->getByIdentifier($link->getId());
    }

    public function testGetByIdentifier()
    {
        $link = $this->createAndReturnCreatedLink();

        $foundedLink = $this->linkService->getByIdentifier($link->getId());

        $this->assertInstanceOf(LinkInterface::class, $foundedLink);
    }

    public function testGetByIdentifierWithException()
    {
        $this->expectException(NotFoundHttpException::class);
        $this->expectExceptionMessage(ExceptionMessages::LINK_NOT_FOUND);

        $this->linkService->getByIdentifier(new LinkIdentifier('123QWDasAa'));
    }

    private function createAndReturnCreatedLink(): LinkInterface
    {
        $createDto = new LinkCreateDto('https://link.co', 'My link', ['The tag']);
        $this->linkService->create($createDto);

        return $this->linkRepository->findOneBy([
            'originalUrl' => 'https://link.co',
            'title' => 'My link'
        ]);
    }
}
