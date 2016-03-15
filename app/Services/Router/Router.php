<?php

namespace App\Services\Router;

use App\Services\Config\Config;
use App\Support\Collection;
use App\Support\Http\Request;
use App\Support\MethodTypeHintResolver;

/**
 * Class Router
 *
 * @package    App\Services\Router
 * @subpackage App\Services\Router\Router
 */
class Router
{

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var MethodTypeHintResolver
     */
    protected $resolver;

    /**
     * @var Collection|Route[]
     */
    protected $routes;


    /**
     * Constructor.
     *
     * @param Config                 $config
     * @param MethodTypeHintResolver $resolver
     */
    public function __construct(Config $config, MethodTypeHintResolver $resolver)
    {
        $this->config   = $config;
        $this->resolver = $resolver;

        $this->parseRoutes($config);
    }

    /**
     * @param Config $config
     */
    protected function parseRoutes(Config $config)
    {
        $this->routes = new Collection();

        foreach ($config->get('routes', []) as $route) {
            $this->routes->add(new Route($route));
        }
    }

    /**
     * @param Request $request
     *
     * @return Route
     * @throws NoRouteFoundException
     */
    public function route(Request $request)
    {
        $requestPath  = $request->getRequestPath();
        $requestType  = $request->getRequestMethod();

        foreach ($this->routes as $route) {
            if ($route->matches($requestType, $requestPath)) {
                $route->setArguments(
                    $this->resolver->resolveArguments($route->getController(), $route->getMethod())
                );

                return $route;
            }
        }

        throw new NoRouteFoundException($requestType, $requestPath);
    }
}
