<?php

namespace App\Entity;

/**
 *
 */
interface EntityInterface
{
    // ретёрн тайп со стрингом - костыль, ибо не успел разобраться
    // как достать сущность с объектом из базы
    public function getId(): IdentifierInterface|string;
}