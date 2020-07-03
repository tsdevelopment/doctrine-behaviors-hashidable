<?php

namespace TSDevelopment\DoctrineBehaviorsHashidable\EventListener;

use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\Common\Persistence\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use Hashids\HashidsInterface;
use TSDevelopment\DoctrineBehaviorsHashidable\Contract\Entity\HashidableInterface;

/**
 * HashIdsListener.
 */
final class HashIdsListener implements EventSubscriberInterface
{
    /** @var string */
    const FIELD_NAME = 'hashId';

    /** @var HashidsInterface */
    private $hashidsInterface;

    public function __construct(HashidsInterface $hashidsInterface)
    {
        $this->hashidsInterface = $hashidsInterface;
    }

    /**
     * Get subscribed events.
     */
    public function getSubscribedEvents(): array
    {
        return [
            Events::loadClassMetadata => 'loadClassMetadata',
            Events::postPersist => 'postPersist',
        ];
    }

    /**
     * Add 'hashid' field if not exists.
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $loadClassMetadataEventArgs): void
    {
        $classMetadata = $loadClassMetadataEventArgs->getClassMetadata();
        if ($classMetadata->reflClass === null) {
            // Class has not yet been fully built, ignore this event
            return;
        }

        if (!is_a($classMetadata->reflClass->getName(), HashidableInterface::class, true)) {
            return;
        }

        if (!$classMetadata->hasField(self::FIELD_NAME)) {
            $classMetadata->mapField([
                'fieldName' => self::FIELD_NAME,
                'type' => 'string',
                'nullable' => true,
            ]);
        }
    }

    /**
     * After entity has been created.
     */
    public function postPersist(LifecycleEventArgs $lifecycleEventArgs)
    {
        $entity = $lifecycleEventArgs->getEntity();
        if (!$entity instanceof HashidableInterface) {
            return;
        }

        $hashidableFieldName = $entity->getHashidableField();
        $objectManager = $lifecycleEventArgs->getObjectManager();

        $hashId = $this->hashidsInterface->encode(call_user_func([$entity, 'get' . ucfirst($hashidableFieldName)]));

        // Set hash
        $entity->setHashId($hashId);

        // Update entity
        $objectManager->persist($entity);
        $objectManager->flush();
    }
}
