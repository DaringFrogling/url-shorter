<?php

namespace App\Tests\Unit\Entity\Link;

use App\Entity\Link\Link;
use PHPUnit\Framework\TestCase;

/**
 *
 */
class LinkTest extends TestCase
{
    private Link $link;

    protected function setUp(): void
    {
        parent::setUp();

        $this->link = new Link(
            'https://link.to/resource',
            'Link to resource',
        );

    }

    public function test__construct()
    {
        $this->assertIsString($this->link->getOriginalUrl());
        $this->assertEquals('https://link.to/resource', $this->link->getOriginalUrl());

        $this->assertIsString($this->link->getTitle());
        $this->assertEquals('Link to resource', $this->link->getTitle());

        $this->assertNotNull($this->link->getTags());
        $this->assertEmpty($this->link->getTags());

        $this->assertInstanceOf(\DateTimeInterface::class, $this->link->getCreatedAt());
        
        $this->assertNull($this->link->getUpdatedAt());
    }

    public function testUpdate()
    {
        $this->link->update(
            'https://new-link.to/resource',
            'New title',
            ['link', 'resource']
        );

        $this->assertEquals('https://new-link.to/resource', $this->link->getOriginalUrl());

        $this->assertEquals('New title', $this->link->getTitle());

        $this->assertNotEmpty($this->link->getTitle());
        $this->assertCount(2, $this->link->getTags());

        $this->assertNotNull($this->link->getUpdatedAt());
    }
}
