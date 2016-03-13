<?php

namespace App;

use App\Support\Collection;

/**
 * Class Request
 *
 * @package    App
 * @subpackage App\Request
 */
class Request
{

    /**
     * @var Collection
     */
    protected $query;

    /**
     * @var Collection
     */
    protected $request;

    /**
     * @var Collection
     */
    protected $server;

    /**
     * Constructor.
     *
     * @param array $query
     * @param array $request
     * @param array $server
     */
    public function __construct(array $query = null, array $request = null, array $server = null)
    {
        $this->query   = new Collection($query ?: $_GET);
        $this->request = new Collection($request ?: $_POST);
        $this->server  = new Collection($server ?: $_SERVER);
    }

    /**
     * @return static
     */
    public static function newFromGlobals()
    {
        return new static($_GET, $_POST, $_SERVER);
    }

    /**
     * @return Collection
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @return Collection
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return Collection
     */
    public function getServer()
    {
        return $this->server;
    }
}
