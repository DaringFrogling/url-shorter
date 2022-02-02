<?php

namespace App\Tests\Integration;

use App\Dto\Link\LinkCreateDto;
use App\Entity\Link\LinkInterface;
use App\Repository\Link\LinkRepository;
use App\Services\Link\LinkService;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class BaseIntegrationTestCase extends WebTestCase
{
    protected EntityManager $em;

    protected function setUp(): void
    {
        parent::setUp();

        $this->em = self::getContainer()->get('doctrine.orm.default_entity_manager');
    }

    public function createAndReturnCreatedLink(): LinkInterface
    {
        $linkService = self::getContainer()->get(LinkService::class);
        $linkRepository = self::getContainer()->get(LinkRepository::class);

        $createDto = new LinkCreateDto('https://link.co', 'My link', ['The tag']);
        $linkService->create($createDto);

        $links = $linkRepository->findBy(
            ['title' => 'My link'],
            1,
        );

        return $links[0];
    }
}