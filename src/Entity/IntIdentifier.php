<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embeddable;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;

#[Embeddable]
class IntIdentifier implements IdentifierInterface
{
    public function __construct(
        #[
            Id,
            GeneratedValue(strategy: 'AUTO'),
            Column(type: 'integer', columnDefinition: false),
        ]
        private int $id
    ) {
    }

    public function getValue(): string
    {
        return $this->id;
    }

    public function equals(IdentifierInterface $identifier): bool
    {
        return $identifier->getValue() === $this->id;
    }
}