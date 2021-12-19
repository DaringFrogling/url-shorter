<?php

namespace App\Entity;

use App\ConstantBag\ExceptionMessages;
use Doctrine\ORM\Mapping\Embeddable;

#[Embeddable]
class LinkIdentifier implements IdentifierInterface
{
    private string $value;

    public function __construct(string $value)
    {
        if (!preg_match("/\w{10}/", $value)) {
            throw new \InvalidArgumentException(ExceptionMessages::INVALID_IDENTIFIER_VALUE);
        }

        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function equals(IdentifierInterface $identifier): bool
    {
        return $identifier->getValue() === $this->value;
    }
}