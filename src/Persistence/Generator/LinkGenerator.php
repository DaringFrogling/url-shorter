<?php

namespace App\Persistence\Generator;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Id\AbstractIdGenerator;

class LinkGenerator extends AbstractIdGenerator
{
    /**
     * @inheritDoc
     *
     * @throws \Exception
     * @throws \InvalidArgumentException
     */
    public function generate(EntityManager $em, $entity)
    {
        $bytes = random_bytes(5);

        return bin2hex($bytes);
    }
}