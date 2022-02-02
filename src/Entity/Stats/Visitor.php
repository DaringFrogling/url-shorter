<?php

namespace App\Entity\Stats;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embeddable;

#[Embeddable]
class Visitor
{
    public function __construct(
        #[Column(name: 'ip_address', type: 'string')]
        private string $ip,

        #[Column(name: 'user_agent', type: 'json')]
        private array $userAgent,
    ) {
    }

    public function getIp(): string
    {
        return $this->ip;
    }

    public function getUserAgent(): array
    {
        return $this->userAgent;
    }
}