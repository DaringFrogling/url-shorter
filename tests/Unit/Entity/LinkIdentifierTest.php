<?php

namespace App\Tests\Unit\Entity;

use App\Entity\LinkIdentifier;
use PHPUnit\Framework\TestCase;

/**
 * 
 */
class LinkIdentifierTest extends TestCase
{
    public function test__construct()
    {
        $identifier = new LinkIdentifier('12ASDca12a');

        $this->assertEquals('12ASDca12a', $identifier->getValue());
    }

    public function test__constructWithException()
    {
        $this->expectException(\InvalidArgumentException::class);
        new LinkIdentifier('12A_SDca12a');

        $this->expectException(\InvalidArgumentException::class);
        new LinkIdentifier('12AsSD');

        $this->expectException(\InvalidArgumentException::class);
        new LinkIdentifier('12asad12ewaAsSD');
    }
}
