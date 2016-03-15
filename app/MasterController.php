<?php

namespace App;

use App\Contracts\Controllers\ContainerAware;
use App\Services\Router\Route;
use App\Services\Router\Router;
use App\Support\Http\Request;
use Aura\Di\ContainerInterface;
use Aura\Web\Response;

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
     * @return Response
     */
    public function execute(Request $request)
    {
        try {
            /** @var Route $route */
            $route = $this->router->route($request);

            $controller = $this->di->newInstance($route->getController());
            $args       = $route->getArguments();

            if ($controller instanceof ContainerAware) {
                $controller->setContainer($this->di);
            }

            $response = call_user_func_array([$controller, $route->getMethod()], $args);

            if (!$response instanceof Response) {
                throw new \RuntimeException(
                    sprintf('%s::%s did not return a response object', $route->getController(), $route->getMethod())
                );
            }

            return $response;

        } catch (\Exception $e) {
            echo $e->getMessage(), '<br />';
            echo '<pre>', print_r($e->getTraceAsString(), 1), '</pre>';
        }
    }
}
