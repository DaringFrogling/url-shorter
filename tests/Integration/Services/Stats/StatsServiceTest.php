<?php

namespace App\Tests\Integration\Services\Stats;

use App\Dto\Stats\FollowLinkDto;
use App\Services\Stats\StatsService;
use App\Services\Stats\StatsServiceInterface;
use App\Tests\Integration\BaseIntegrationTestCase;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityNotFoundException;

class StatsServiceTest extends BaseIntegrationTestCase
{
    private StatsServiceInterface $statsService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->statsService = self::getContainer()->get(StatsService::class);
    }

    /**
     * @throws Exception
     */
    public function testFollowLink(): void
    {
        $link = $this->createAndReturnCreatedLink();
        $followLinkDto = new FollowLinkDto(
            $link,
            '127.0.0.1',
            [
                'parent' => 'Chrome v96',
                'browser' => 'Chrome',
                'version' => 'v96'
            ]
        );
        $this->statsService->followLink($followLinkDto);

        $sql = 'SELECT * FROM link_stats WHERE ip_address = \'127.0.0.1\'';
        $stats = $this->em->getConnection()->executeQuery($sql)->fetchAssociative();

        $this->assertNotFalse($stats);
    }

    /**
     * @throws EntityNotFoundException
     * @throws Exception
     */
    public function testCountTotalAndUniqueVisitsWithIdentifier(): void
    {
        [$stats, $link] = $this->prepareData();
        [$total, $unique] = $this->statsService->countTotalAndUniqueVisits($link->getIdentifier());

        $this->assertNotFalse($total);
        $this->assertNotFalse($unique);

        // todo finish asserts
    }

    /**
     * @throws Exception
     */
    private function prepareData(): array
    {
        $link = $this->createAndReturnCreatedLink();
        $followLinkDto = new FollowLinkDto(
            $link,
            '178.0.0.1',
            [
                'parent' => 'Safari 14',
                'browser' => 'Safari',
                'version' => '14'
            ]
        );
        $this->statsService->followLink($followLinkDto);
        $this->statsService->followLink($followLinkDto);

        $secondLink = $this->createAndReturnCreatedLink();
        $followSecondLinkDto = new FollowLinkDto(
            $secondLink,
            '127.0.0.1',
            [
                'parent' => 'Chrome v96',
                'browser' => 'Chrome',
                'version' => 'v96'
            ]
        );
        $this->statsService->followLink($followSecondLinkDto);

        $sql = 'SELECT * FROM link_stats';
        $stats = $this->em->getConnection()->executeQuery($sql)->fetchAssociative();

        return [$stats, $link];
    }
}