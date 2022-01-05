<?php

namespace App\Persistence\Type;

use App\Entity\LinkIdentifier;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use JetBrains\PhpStorm\Pure;

class LinkIdentifierType extends Type
{
    private const LINK = 'link';

    public function getName(): string
    {
        return self::LINK;
    }

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        return 'LINK';
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): LinkIdentifier
    {
        return new LinkIdentifier($value);
    }

    #[Pure] public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value instanceof LinkIdentifier) {
            $value = $value->getValue();
        }

        return $value;
    }

    public function canRequireSQLConversion(): bool
    {
        return true;
    }
}