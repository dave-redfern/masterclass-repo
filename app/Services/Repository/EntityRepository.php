<?php

namespace App\Services\Repository;

use App\Services\Config\Config;
use App\Services\DB\Connection;
use App\Services\DB\EntityManager;
use App\Services\Persister\PersisterInterface;
use App\Support\Collection;
use PDO;

/**
 * Class EntityRepository
 *
 * @package    App\Services\Repository
 * @subpackage App\Services\Repository\EntityRepository
 */
abstract class EntityRepository implements RepositoryInterface
{

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var PersisterInterface
     */
    protected $persister;

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
     * @param EntityManager      $em
     * @param PersisterInterface $persister
     * @param Config             $config
     * @param string             $entity
     */
    public function __construct(EntityManager $em, PersisterInterface $persister, Config $config, $entity)
    {
        $this->em         = $em;
        $this->persister  = $persister;
        $this->config     = $config;
        $this->entityName = $entity;
    }

    /**
     * @return string
     */
    public function getEntityName()
    {
        return $this->entityName;
    }

    /**
     * @return PersisterInterface
     */
    public function getPersister()
    {
        return $this->persister;
    }

    /**
     * @param array $where
     *
     * @return integer
     */
    public function count(array $where = [])
    {
        $parameters = [];
        $whereSql   = [];
        $query      = sprintf('SELECT COUNT(*) AS cnt FROM %s', $this->getMappedTable());

        foreach ($where as $field => $value) {
            if (is_array($value)) {
                $whereSql[] = sprintf('%s IN (%s)', $field, implode(',', array_fill(0, count($value), '?')));
                $parameters = array_merge($parameters, $value);
            } else {
                $whereSql[]   = sprintf('%s = ?', $field);
                $parameters[] = $value;
            }
        }

        if (count($whereSql) > 0) {
            $query .= ' WHERE ' . implode(' AND ', $whereSql);
        }

        $stmt = $this->em->getConnection()->prepare($query);
        $stmt->execute($parameters);

        return $stmt->fetchColumn();
    }

    /**
     * Fetches an instance of the object matching id
     *
     * @param integer $id
     *
     * @return null|object
     */
    public function find($id)
    {
        $stmt = $this->em->getConnection()->prepare(
            sprintf(
                'SELECT * FROM %s WHERE id = :id LIMIT 1',
                $this->getMappedTable()
            )
        );
        $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, $this->getEntityName());
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        if (false !== $object = $stmt->fetch()) {
            return $object;
        }

        return null;
    }

    /**
     * Finds all instances of the objects
     *
     * @return Collection
     */
    public function findAll()
    {
        $stmt = $this->em->getConnection()->prepare(
            sprintf('SELECT * FROM %s', $this->getMappedTable())
        );
        $stmt->execute();

        $results = $stmt->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, $this->getEntityName());

        return new Collection($results);
    }

    /**
     * @param array    $where
     * @param array    $orderBy
     * @param null|int $offset
     * @param null|int $limit
     *
     * @return Collection
     */
    public function findBy(array $where, array $orderBy = [], $offset = null, $limit = null)
    {
        $parameters = [];
        $query      = sprintf('SELECT * FROM %s', $this->getMappedTable());
        $whereSql   = [];
        $orderSql   = [];

        foreach ($where as $field => $value) {
            if (is_array($value)) {
                $whereSql[] = sprintf('%s IN (%s)', $field, implode(',', array_fill(0, count($value), '?')));
                $parameters = array_merge($parameters, $value);
            } else {
                $whereSql[]   = sprintf('%s = ?', $field);
                $parameters[] = $value;
            }
        }

        if (count($whereSql) > 0) {
            $query .= ' WHERE ' . implode(' AND ', $whereSql);
        }
        if (count($orderBy) > 0) {
            $query .= ' ORDER BY ';

            foreach ($orderBy as $field => $dir) {
                $orderSql[] = sprintf('%s %s', $field, $dir);
            }

            $query .= implode(', ', $orderSql);
        }

        if ($offset && $limit) {
            $query .= sprintf(' OFFSET %d LIMIT %d', $offset, $limit);
        } elseif ($limit) {
            $query .= sprintf(' LIMIT %d', $limit);
        }

        $stmt = $this->em->getConnection()->prepare($query);
        $stmt->execute($parameters);

        $results = $stmt->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, $this->getEntityName());

        return new Collection($results);
    }

    /**
     * @param array    $where
     * @param array    $orderBy
     *
     * @return null|object
     */
    public function findOneBy(array $where, array $orderBy = [])
    {
        if (null !== $result = $this->findBy($where, $orderBy, 0, 1)->first()) {
            return $result;
        }

        return null;
    }

    /**
     * @return string
     */
    protected function getMappedTable()
    {
        return $this->config->get('mappings')[$this->getEntityName()];
    }

    /**
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        return $this->em;
    }

    /**
     * @param string $class
     *
     * @return RepositoryInterface
     */
    protected function getRepository($class)
    {
        return $this->em->getRepository($class);
    }
}
