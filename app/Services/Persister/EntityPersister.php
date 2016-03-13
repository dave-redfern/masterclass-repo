<?php

namespace App\Services\Persister;

use App\Services\Config\Config;
use App\Services\DB\Connection;

/**
 * Class EntityPersister
 *
 * @package    App\Services\Persister
 * @subpackage App\Services\Persister\EntityPersister
 */
abstract class EntityPersister implements PersisterInterface
{

    /**
     * @var Connection
     */
    protected $connection;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var string
     */
    protected $entityName;

    /**
     * Constructor.
     *
     * @param Connection $connection
     * @param Config     $config
     * @param string     $entity
     */
    public function __construct(Connection $connection, Config $config, $entity)
    {
        $this->connection = $connection;
        $this->config     = $config;
        $this->entityName = $entity;
    }

    /**
     * @param mixed $entity
     *
     * @throws \InvalidArgumentException
     */
    protected function isValidPersistable($entity)
    {
        if (!$entity instanceof $this->entityName) {
            throw new \InvalidArgumentException(
                sprintf('"%s" is not valid for this persister', get_class($entity))
            );
        }
    }

    /**
     * @return string
     */
    protected function getMappedTable()
    {
        return $this->config->get('mappings')[$this->getEntityName()];
    }

    /**
     * @return string
     */
    public function getEntityName()
    {
        return $this->entityName;
    }



    /**
     * @param mixed $entity
     *
     * @return boolean
     */
    public function save($entity)
    {
        $this->isValidPersistable($entity);

        return $this->_save($entity);
    }

    /**
     * @param mixed $entity
     *
     * @return boolean
     */
    public function update($entity)
    {
        $this->isValidPersistable($entity);

        return $this->_update($entity);
    }

    /**
     * @param mixed $entity
     *
     * @return boolean
     */
    public function destroy($entity)
    {
        $this->isValidPersistable($entity);

        return $this->_destroy($entity);
    }



    /**
     * @param mixed $entity
     *
     * @return boolean
     */
    abstract protected function _save($entity);

    /**
     * @param mixed $entity
     *
     * @return boolean
     */
    abstract protected function _update($entity);

    /**
     * @param mixed $entity
     *
     * @return boolean
     */
    abstract protected function _destroy($entity);

    /**
     * @param mixed  $entity
     * @param string $id
     *
     * @return mixed
     */
    protected function updatePrimaryKey($entity, $id)
    {
        $reflect = new \ReflectionObject($entity);

        $propId  = $reflect->getProperty('id');
        $propId->setAccessible(true);
        $propId->setValue($entity, $id);

        return $entity;
    }
}
