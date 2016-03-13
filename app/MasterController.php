<?php

namespace App;

use App\Services\Router\Route;
use App\Services\Router\Router;
use App\Support\Http\Request;
use Aura\Di\ContainerInterface;

/**
 * Class MasterController
 *
 * @package    App
 * @subpackage App\MasterController
 */
class MasterController
{

    /**
     * @var ContainerInterface
     */
    protected $di;

    /**
     * @var Router
     */
    protected $router;

    /**
     * Constructor.
     *
     * @param ContainerInterface $di
     * @param Router             $router
     */
    public function __construct(ContainerInterface $di, Router $router)
    {
        $this->di     = $di;
        $this->router = $router;
    }

    /**
     * @param Request $request
     *
     * @return string
     */
    public function execute(Request $request)
    {
        try {
            /** @var Route $route */
            $route = $this->router->route($request);

            if ($route) {
                $controller = $this->di->newInstance($route->getController());
                $args       = $route->getArguments();

                return call_user_func_array([$controller, $route->getMethod()], $args);
            } else {
                throw new \RuntimeException('No route defined');
            }

        } catch (\Exception $e) {
            echo $e->getMessage(), '<br />';
            echo '<pre>', print_r($e->getTraceAsString(), 1), '</pre>';
        }
    }
}
