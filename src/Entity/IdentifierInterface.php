<?php

namespace App\Entity;

interface IdentifierInterface
{
    public function getValue(): string;

    public function equals(IdentifierInterface $identifier): bool;
}