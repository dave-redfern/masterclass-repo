<?php

namespace App\Services\Router;

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
     * @param MethodTypeHintResolver $resolver
     * @param array                  $routes
     */
    public function __construct(MethodTypeHintResolver $resolver, array $routes = [])
    {
        $this->resolver = $resolver;
        $this->parseRoutes($routes);
    }

    /**
     * @param array $routes
     */
    protected function parseRoutes(array $routes)
    {
        $this->routes = new Collection();

        foreach ($routes as $route) {
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
        $requestPath = $request->getRequestPath();
        $requestType = $request->getRequestMethod();
        $requestArgs = $request->getRequestArguments();

        foreach ($this->routes as $route) {
            if ($route->matches($requestType, $requestPath)) {
                $route->setArguments(
                    $this->resolver->resolveArguments($route->getController(), $route->getMethod(), $requestArgs)
                );

                return $route;
            }
        }

        throw new NoRouteFoundException($requestType, $requestPath);
    }
}
