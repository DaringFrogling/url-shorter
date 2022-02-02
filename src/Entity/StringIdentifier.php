<?php

namespace App\Entity;

use App\ConstantBag\ExceptionMessages;
use App\Persistence\Generator\LinkGenerator;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\CustomIdGenerator;
use Doctrine\ORM\Mapping\Embeddable;
use Doctrine\ORM\Mapping\GeneratedValue;
use http\Exception\UnexpectedValueException;

#[Embeddable]
class StringIdentifier implements IdentifierInterface
{
    #[
        GeneratedValue(strategy: 'CUSTOM'),
        CustomIdGenerator(class: LinkGenerator::class),
        Column(type: 'string', unique: true),
    ]
    private string $id;

    public function __construct(string $value)
    {
        if (!preg_match("/\w{10}/", $value)) {
            throw new UnexpectedValueException(ExceptionMessages::INVALID_IDENTIFIER_VALUE);
        }

        $this->id = $value;
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