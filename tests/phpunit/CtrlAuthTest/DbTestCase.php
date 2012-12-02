<?php

namespace CtrlAuthTest;

use Zend\ServiceManager\ServiceManager;
use Zend\Mvc\Service\ServiceManagerConfig;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

abstract class DbTestCase extends \CtrlTest\DbTestCase
{
    protected $defaultMetaData = '/config/entities';

    protected function getEntityManager(array $metadata)
    {
        if (!$this->entityManager) {
            $config = Setup::createXMLMetadataConfiguration(
                $this->getMetaData($metadata),
                true,
                TESTS_DOCTRINE_PROXY_DIR
            );

            //$conn = array('driver' => 'pdo_sqlite', 'path' =>  __DIR__ . '/db/test.db');
            $conn = array('driver' => 'pdo_sqlite', 'memory' => true);

            $this->entityManager = EntityManager::create($conn, $config);
        }
        return $this->entityManager;
    }

    protected function getMetaData(array $metadata)
    {
        array_unshift($metadata, array(__DIR__.'/../../../'.$this->defaultMetaData));
        return $metadata;
    }
}
