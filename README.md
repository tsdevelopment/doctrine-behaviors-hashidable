# Doctrine Behaviors Hashidable

This PHP library provides traits and interfaces that add hashidable behaviour (known from youtube video IDs) to Doctrine entities.

## Install

```bash
composer require tsdevelopment/doctrine-behaviors-hashidable
```

## Usage

All you have to do is to define a Doctrine entity:

- implement `HashidableInterface` interface
- add `HashidableTrait` trait

```php
<?php

declare(strict_types=1);

namespace App\Entity;

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
```

As default the `id` property will be used to create the `hashId`. But you can override it:

```php
<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use TSDevelopment\DoctrineBehaviorsHashidable\Contract\Entity\HashidableInterface;
use TSDevelopment\DoctrineBehaviorsHashidable\Traits\HashidableTrait;

/**
 * @ORM\Entity
 */
class HashidableCustomGetterEntity implements HashidableInterface
{
    use HashidableTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    private $customId;

    public function getCustomId(): int
    {
        return $this->customId;
    }

    public function getHashidableField(): string
    {
        return 'customId';
    }
}

```

## Configuration

Internally the bundle uses https://github.com/roukmoute/hashids-bundle to generate
hashids. See the `configuration` part for all possible options.
