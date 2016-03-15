<?php

namespace App;

use App\Contracts\Controllers\ContainerAware as ContainerAwareContract;
use App\Services\Router\Route;
use App\Services\Router\Router;
use App\Support\Http\Request;
use App\Support\Traits\Controller\ContainerAware;
use App\Support\Traits\Controller\CreateResponse;
use App\Support\Traits\Controller\RenderView;
use Aura\Di\ContainerInterface;
use Aura\Web\Response;

/**
 * Class MasterController
 *
 * @package    App
 * @subpackage App\MasterController
 */
class MasterController implements ContainerAwareContract
{

    use ContainerAware;
    use CreateResponse;
    use RenderView;

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

            if ($controller instanceof ContainerAwareContract) {
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
            $response = $this->view('errors/error.twig', ['e' => $e]);
            $response->status->setCode(500);
            $response->status->setPhrase('Internal Server Error');

            return $response;
        }
    }
}
