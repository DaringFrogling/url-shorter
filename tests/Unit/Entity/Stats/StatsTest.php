<?php

namespace App\Tests\Unit\Entity\Stats;

use App\Entity\Link\Link;
use App\Entity\Stats\Stats;
use App\Entity\Stats\StatsInterface;
use App\Entity\Stats\Visitor;
use PHPUnit\Framework\TestCase;

class StatsTest extends TestCase
{
    private StatsInterface $stats;

    protected function setUp(): void
    {
        parent::setUp();

        $this->stats = new Stats(
            new Link(
                'https://soundcloud.com',
                'soundcloud',
                ['music']
            ),
            new Visitor(
                '127.0.0.1',
                [
                    'parent' => 'Chrome v96',
                    'browser' => 'Chrome',
                    'version' => 'v96'
                ]
            )
        );
    }

    public function test__construct(): void
    {
        $this->assertNotNull($this->stats);
        
        $this->assertNotNull($this->stats->getLink());

        $visitor = $this->stats->getVisitor();
        $this->assertNotNull($visitor);
        $this->assertEquals('127.0.0.1', $visitor->getIp());
        $this->assertNotEmpty($visitor->getUserAgent());
        $this->assertCount(3, $visitor->getUserAgent());

        $this->assertNotNull($this->stats->getVisitedAt());
    }
}
