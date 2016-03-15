<?php

namespace App\Services\DB;

use App\Services\Config\Config;
use App\Services\Repository\RepositoryInterface;
use App\Support\Collection;
use Aura\Di\ContainerInterface;

/**
 * Class EntityManager
 *
 * @package    App\Services\DB
 * @subpackage App\Services\DB\EntityManager
 */
class EntityManager
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
     * @var ContainerInterface
     */
    protected $container;



    /**
     * Constructor.
     *
     * @param Connection         $connection
     * @param Config             $config
     * @param ContainerInterface $container
     */
    public function __construct(Connection $connection, Config $config, ContainerInterface $container)
    {
        $this->connection = $connection;
        $this->config     = new Collection($config->get('repositories', []));
        $this->container  = $container;
    }

    /**
     * @return Connection
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * Get the repository for the specified class
     *
     * @param string $class
     *
     * @return RepositoryInterface
     */
    public function getRepository($class)
    {
        return $this->container->get($this->config->get($class));
    }
}
