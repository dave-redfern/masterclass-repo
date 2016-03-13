<?php

namespace App\Support;

use App\Services\Config\Config;
use PDO;

/**
 * Class Connection
 *
 * @package    App\Support
 * @subpackage App\Support\Connection
 */
class Connection
{

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var PDO
     */
    protected $pdo;

    /**
     * Constructor.
     *
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = new Config($config->get('database', []));
    }



    /**
     * Makes a PDO connection, returning the current connection
     *
     * @return PDO
     */
    public function connect()
    {
        if ($this->pdo instanceof PDO) {
            return $this->pdo;
        }

        return $this->createPdo();
    }

    /**
     * Reconnect to PDO
     *
     * @return PDO
     */
    public function reconnect()
    {
        if ($this->pdo) {
            $this->pdo = null;
        }

        return $this->createPdo();
    }

    /**
     * Executes the statement, returning number of rows affected
     *
     * @param string $query
     *
     * @return int
     */
    public function exec($query)
    {
        return $this->connect()->exec($query);
    }

    /**
     * Executes the statement, returning a statement object
     *
     * @param string $query
     *
     * @return \PDOStatement
     */
    public function query($query)
    {
        return $this->connect()->query($query);
    }

    /**
     * @param string $query
     * @param array  $params
     *
     * @return \PDOStatement
     */
    public function prepare($query, array $params = [])
    {
        return $this->connect()->prepare($query, $params);
    }

    /**
     * @param null|string $name
     *
     * @return string
     */
    public function lastInsertId($name = null)
    {
        return $this->connect()->lastInsertId($name);
    }

    /**
     * Starts a transaction, no nesting supported
     *
     * @return bool
     */
    public function beginTransaction()
    {
        return $this->connect()->beginTransaction();
    }

    /**
     * Commits an open transaction
     *
     * @return bool
     */
    public function commit()
    {
        return $this->connect()->commit();
    }

    /**
     * Rolls back an open transaction
     *
     * @return bool
     */
    public function rollback()
    {
        return $this->connect()->rollBack();
    }

    /**
     * Wraps a transaction around the closure that receives a PDO instance as parameter
     *
     * Returns the result of the Closure on success.
     *
     * @param \Closure $closure
     *
     * @return mixed
     * @throws \Exception
     */
    public function transaction(\Closure $closure)
    {
        $conn = $this->connect();
        $conn->beginTransaction();

        try {
            $return = $closure($conn);

            $conn->commit();

            return $return;
        } catch (\Exception $e) {
            $conn->rollBack();

            throw $e;
        }
    }

    /**
     * @return string
     */
    protected function createDsn()
    {
        return sprintf($this->config->get('dsn'), $this->config->get('host'), $this->config->get('name'));
    }

    /**
     * @return PDO
     */
    protected function createPdo()
    {
        $this->pdo = new PDO($this->createDsn(), $this->config->get('user'), $this->config->get('pass'));
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $this->pdo;
    }
}
