<?php

namespace App\Tests\Unit\Entity;

use App\Entity\StringIdentifier;
use PHPUnit\Framework\TestCase;

/**
 * 
 */
class StringIdentifierTest extends TestCase
{
    public function test__construct(): void
    {
        $identifier = new StringIdentifier('12ASDca12a');

        $this->assertEquals('12ASDca12a', $identifier->getValue());
    }

    public function test__constructWithException(): void
    {
        $this->expectException(\UnexpectedValueException::class);
        new StringIdentifier('12A_SDca12a');

        $this->expectException(\UnexpectedValueException::class);
        new StringIdentifier('12AsSD');

        $this->expectException(\UnexpectedValueException::class);
        new StringIdentifier('12asad12ewaAsSD');
    }
}
