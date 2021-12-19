<?php

namespace App\Entity\Stats;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embeddable;

#[Embeddable]
class Visitor
{
    public function __construct(
        #[Column]
        private string $ip,

        #[Column]
        private string $userAgent,
    ) {
    }

    /**
     * @return string
     */
    public function getIp(): string
    {
        return $this->ip;
    }

    /**
     * @return string
     */
    public function getUserAgent(): string
    {
        return $this->userAgent;
    }
}