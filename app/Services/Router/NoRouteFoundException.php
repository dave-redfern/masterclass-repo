<?php

namespace App\Services\Router;

/**
 * Class NoRouteFoundException
 *
 * @package    App\Services\Router
 * @subpackage App\Services\Router\NoRouteFoundException
 */
class NoRouteFoundException extends \Exception
{

    /**
     * Constructor.
     *
     * @param string $type
     * @param string $path
     */
    public function __construct($type, $path)
    {
        parent::__construct(sprintf('No route found for %s: %s', $type, $path), 404, null);
    }
}
