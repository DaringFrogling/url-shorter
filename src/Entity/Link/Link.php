<?php

namespace App\Entity\Link;

use App\Entity\IdentifierInterface;
use App\Entity\LinkIdentifier;
use App\Persistence\Generator\LinkGenerator;
use App\Repository\Link\LinkRepository;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

#[
    ORM\Entity(repositoryClass: LinkRepository::class),
    ORM\Table(name: 'link')
]
class Link implements LinkInterface
{
    #[
        ORM\Id,
        ORM\Column,
        ORM\GeneratedValue(strategy: 'CUSTOM'),
        ORM\CustomIdGenerator(class: LinkGenerator::class),
    ]
    private IdentifierInterface $id;

    /**
     * Link constructor.
     *
     * @param string $originalUrl
     * @param string $title
     * @param array $tags
     * @param DateTimeInterface $createdAt
     * @param DateTimeInterface|null $updatedAt
     */
    public function __construct(
        #[ORM\Column]
        private string $originalUrl,

        #[ORM\Column]
        private string $title,

        #[ORM\Column(type: 'simple_array')]
        private array $tags = [],

        #[ORM\Column]
        private DateTimeInterface $createdAt = new DateTimeImmutable('now'),

        #[ORM\Column]
        private ?DateTimeInterface $updatedAt = null,
    ) {
    }

    public function getId(): IdentifierInterface
    {
        return $this->id;
    }

    public function getOriginalUrl(): string
    {
        return $this->originalUrl;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getTags(): array
    {
        return $this->tags;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * @inheritDoc
     */
    public function update(string $originalUrl, string $title, array $tags): self
    {
        $this->originalUrl = $originalUrl;
        $this->title = $title;
        $this->tags = $tags;
        $this->updatedAt = new DateTimeImmutable('now');

        return $this;
    }
}
