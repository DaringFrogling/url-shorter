<?php

namespace App\Tests\Integration\Services\Link;

use App\ConstantBag\ExceptionMessages;
use App\Dto\Link\LinkCreateDto;
use App\Dto\Link\LinkUpdateDto;
use App\Entity\IntIdentifier;
use App\Entity\Link\LinkInterface;
use App\Entity\StringIdentifier;
use App\Repository\Link\LinkRepository;
use App\Services\Link\LinkService;
use App\Services\Link\LinkServiceInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

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
    }

    /**
     * @throws EntityNotFoundException
     */
    public function testUpdate(): void
    {
        $link = $this->createAndReturnCreatedLink();
        $updateDto = new LinkUpdateDto(
            $link->getShortenedUri(),
            'https://new-link.co',
            'new title',
            ['new-tag']
        );

        $this->linkService->update($updateDto);
        $updatedLink = $this->linkService->getByShortenedUri($link->getShortenedUri());

        $this->assertNotNull($link);
        $this->assertEquals($updatedLink->getIdentifier(), $link->getIdentifier());
        $this->assertEquals($updatedLink->getShortenedUri(), $link->getShortenedUri());
        $this->assertEquals('https://new-link.co', $updatedLink->getOriginalUrl());
        $this->assertEquals('new title', $updatedLink->getTitle());
        $this->assertEquals(['new-tag'], $updatedLink->getTags());
    }

    /**
     * @throws EntityNotFoundException
     */
    public function testDelete(): void
    {
        $link = $this->createAndReturnCreatedLink();
        $this->linkService->delete($link->getShortenedUri());

        $this->expectException(EntityNotFoundException::class);
        $this->expectExceptionMessage(ExceptionMessages::LINK_NOT_FOUND);

        $this->linkService->getByShortenedUri($link->getShortenedUri());
    }

    /**
     * @throws EntityNotFoundException
     */
    public function testGetByIdentifier(): void
    {
        $link = $this->createAndReturnCreatedLink();

        $foundedLink = $this->linkService->getByIdentifier($link->getIdentifier());

        $this->assertNotNull($foundedLink);
    }

    public function testGetByIdentifierWithException(): void
    {
        $this->expectException(EntityNotFoundException::class);
        $this->expectExceptionMessage(ExceptionMessages::LINK_NOT_FOUND);

        $this->linkService->getByIdentifier(new IntIdentifier(99));
    }

    /**
     * @throws EntityNotFoundException
     */
    public function testGetByShortenedIdentifier(): void
    {
        $link = $this->createAndReturnCreatedLink();

        $foundedLink = $this->linkService->getByShortenedUri($link->getShortenedUri());

        $this->assertNotNull($foundedLink);
    }


    public function testGetByShortenedIdentifierWithException(): void
    {
        $this->expectException(EntityNotFoundException::class);
        $this->expectExceptionMessage(ExceptionMessages::LINK_NOT_FOUND);

        $this->linkService->getByShortenedUri(new StringIdentifier('123QWDasAa'));
    }

    private function createAndReturnCreatedLink(): LinkInterface
    {
        $createDto = new LinkCreateDto('https://link.co', 'My link', ['The tag']);
        $this->linkService->create($createDto);

        $links = $this->linkRepository->findBy(
            ['title' => 'My link'],
            1,
        );

        return $links[0];
    }
}
