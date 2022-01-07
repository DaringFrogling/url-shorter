<?php

namespace App\Entity\Link;

use App\Entity\IdentifierInterface;
use App\Entity\LinkIdentifier;
use App\Repository\Link\LinkRepository;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embedded;
use Doctrine\ORM\Mapping\Entity;

#[Entity(repositoryClass: LinkRepository::class)]
class Link implements LinkInterface
{
    #[Embedded(class: LinkIdentifier::class, columnPrefix: false)]
    private IdentifierInterface $identifier;

    #[Column(type: 'string')]
    private string $title;

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
        #[Column(name: 'original_url', type: 'string')]
        private string $originalUrl,

        string $title,

        #[Column(type: 'simple_array')]
        private array $tags = [],

        #[Column(name: 'created_at', type: 'datetime')]
        private DateTimeInterface $createdAt = new DateTimeImmutable('now'),

        #[Column(name: 'updated_at', type: 'datetime', nullable: true)]
        private ?DateTimeInterface $updatedAt = null,
    ) {
        $this->title = mb_strtolower($title);
    }

    public function getIdentifier(): IdentifierInterface
    {
        return $this->identifier;
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
