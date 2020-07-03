<?php

declare(strict_types=1);

namespace TSDevelopment\DoctrineBehaviorsHashidable\Traits;

trait HashidableMethodsTrait
{
    public function getHashId(): string
    {
        return $this->hashId;
    }

    public function getHashidableField(): string
    {
        return 'id';
    }

    public function setHashId(string $hashId): void
    {
        $this->hashId = $hashId;
    }
}
