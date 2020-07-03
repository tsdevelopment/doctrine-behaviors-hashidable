<?php

declare(strict_types=1);

namespace TSDevelopment\DoctrineBehaviorsHashidable\Contract\Entity;

interface HashidableInterface
{
    public function getHashId(): string;

    public function getHashidableField(): string;

    public function setHashId(string $hashId): void;
}
