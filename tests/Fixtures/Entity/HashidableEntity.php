<?php

declare(strict_types=1);

namespace TSDevelopment\DoctrineBehaviorsHashidable\Tests\Fixtures\Entity;

use Doctrine\ORM\Mapping as ORM;
use TSDevelopment\DoctrineBehaviorsHashidable\Contract\Entity\HashidableInterface;
use TSDevelopment\DoctrineBehaviorsHashidable\Traits\HashidableTrait;

/**
 * @ORM\Entity
 */
class HashidableEntity implements HashidableInterface
{
    use HashidableTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    private $id;

    public function getId(): int
    {
        return $this->id;
    }
}
