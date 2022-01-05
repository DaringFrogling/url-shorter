<?php

namespace App\Entity\Link;

use App\Entity\IdentifierInterface;
use App\Persistence\Generator\LinkGenerator;
use App\Repository\Link\LinkRepository;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LinkRepository::class)]
class Link implements LinkInterface
{
    #[
        ORM\Id,
        ORM\Column,
        ORM\GeneratedValue(strategy: 'CUSTOM'),
        ORM\CustomIdGenerator(class: LinkGenerator::class),
    ]
    // тайп со стрингом - костыль, ибо не успел разобраться
    // как достать сущность с объектом из базы
    private IdentifierInterface|string $id;

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
        #[ORM\Column(name: 'original_url', type: 'string')]
        private string $originalUrl,

        #[ORM\Column(type: 'string')]
        private string $title,

        #[ORM\Column(type: 'simple_array')]
        private array $tags = [],

        #[ORM\Column(name: 'created_at', type: 'datetime')]
        private DateTimeInterface $createdAt = new DateTimeImmutable('now'),

        #[ORM\Column(name: 'updated_at', type: 'datetime', nullable: true)]
        private ?DateTimeInterface $updatedAt = null,
    ) {
    }

    public function getId(): IdentifierInterface|string
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
