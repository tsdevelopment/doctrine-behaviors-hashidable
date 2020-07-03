<?php

declare(strict_types=1);

namespace TSDevelopment\DoctrineBehaviorsHashidable\Tests\ORM;

use Doctrine\ORM\Tools\SchemaTool;
use TSDevelopment\DoctrineBehaviorsHashidable\Tests\Fixtures\Entity\HashidableCustomGetterEntity;
use TSDevelopment\DoctrineBehaviorsHashidable\Tests\Fixtures\Entity\HashidableEntity;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Filesystem\Filesystem;

class HashidableTest extends KernelTestCase
{
    /** @var \Doctrine\ORM\EntityManager */
    private $entityManager;

    /**
     * Init database connection.
     */
    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->prepareDatabase();
    }

    /**
     * Test creating hashIds for entites
     */
    public function testAutoSettingHashId(): void
    {
        $entity = new HashidableEntity();

        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        $id = $entity->getId();
        $this->assertNotNull($id);
        $this->assertSame($id, 1);

        $hashId = $entity->getHashId();
        $this->assertNotNull($hashId);
        $this->assertNotEmpty($hashId);
        $this->assertSame($hashId, 'jR');

        $this->entityManager->clear();
    }

    /**
     * Test creating hashIds for entites
     */
    public function testAutoSettingHashIdWithCustomGetter(): void
    {
        $entity = new HashidableCustomGetterEntity();

        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        $id = $entity->getCustomId();
        $this->assertNotNull($id);
        $this->assertSame($id, 1);

        $hashId = $entity->getHashId();
        $this->assertNotNull($hashId);
        $this->assertNotEmpty($hashId);
        $this->assertSame($hashId, 'jR');

        $this->entityManager->clear();
    }

    /**
     * Remove old database and create fresh one.
     */
    private function prepareDatabase()
    {
        $dbParams = $this->entityManager->getConnection()->getParams();
        $dbPath = $dbParams['path'];

        // Remove existing database
        $filesystem = new Filesystem();

        if ($filesystem->exists($dbPath)) {
            $filesystem->remove($dbPath);
        }

        $entites = [
            $this->entityManager->getClassMetadata(HashidableEntity::class),
            $this->entityManager->getClassMetadata(HashidableCustomGetterEntity::class),
        ];

        $tool = new SchemaTool($this->entityManager);
        $tool->createSchema($entites);
    }
}
