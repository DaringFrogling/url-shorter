<?php

namespace App\Entity\Link;

use App\Entity\EntityInterface;
use App\Entity\IdentifierInterface;
use DateTimeInterface;

/**
 *
 */
interface LinkInterface extends EntityInterface
{
    public function getShortenedUri(): IdentifierInterface;

    public function shortenedUri(string $identifier): void;

    public function getOriginalUrl(): string;

    public function getTitle(): string;

    public function getTags(): array;

    public function getCreatedAt(): DateTimeInterface;

    public function getUpdatedAt(): ?DateTimeInterface;

    /**
     * Updates Link entity.
     *
     * @param string $originalUrl
     * @param string $title
     * @param array $tags
     *
     * @return $this
     */
    public function update(string $originalUrl, string $title, array $tags): LinkInterface;
}