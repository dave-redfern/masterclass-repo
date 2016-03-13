<?php

namespace App;

use App\Services\Route;
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
     * Constructor.
     *
     * @param ContainerInterface $di
     */
    public function __construct(ContainerInterface $di)
    {
        $this->di = $di;
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
            $route = $this->di->get('router')->route($request);

            if ($route) {
                $controller = $this->di->newInstance($route->getController());

                $reflect = new \ReflectionMethod($controller, $route->getMethod());
                $params  = $reflect->getParameters();
                $args    = [];

                foreach ($params as $num => $param) {
                    if ($param->getClass()->getName() == Request::class) {
                        $args[$param->getPosition()] = $request;
                    }
                }

                return call_user_func_array([$controller, $route->getMethod()], $args);
            }
        } catch (\Exception $e) {
            echo $e->getMessage(), '<br />';
            echo '<pre>', print_r($e->getTraceAsString(), 1), '</pre>';
        }

        return 'No root defined';
    }
}
