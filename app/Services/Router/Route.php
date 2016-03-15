<?php

namespace App\Services\Router;

use App\Support\Collection;

/**
 * Class Route
 *
 * @package    App\Services\Router
 * @subpackage App\Services\Router\Route
 */
class Route extends Collection
{

    /**
     * Constructor.
     *
     * @param array $collection
     */
    public function __construct(array $collection)
    {
        list($controller, $method) = explode('@', $collection['handler']);

        $collection['controller'] = $controller;
        $collection['method']     = $method;

        parent::__construct($collection);
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->get('path');
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->get('type');
    }

    /**
     * @return string
     */
    public function getHandler()
    {
        return $this->get('handler');
    }

    /**
     * @return string
     */
    public function getController()
    {
        return $this->get('controller');
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->get('method');
    }

    /**
     * @return array
     */
    public function getArguments()
    {
        return $this->get('arguments', []);
    }

    /**
     * @return boolean
     */
    public function getAuth()
    {
        return $this->get('auth', false);
    }

    /**
     * @param array $args
     *
     * @return $this
     */
    public function setArguments(array $args)
    {
        $this->set('arguments', $args);

        return $this;
    }

    /**
     * @param string $requestType
     * @param string $path
     *
     * @return bool
     */
    public function matches($requestType, $path)
    {
        return ($this->getType() == $requestType && $this->getPath() == $path);
    }
}
