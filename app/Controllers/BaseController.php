<?php

namespace App\Controllers;

use App\Contracts\Controllers\ContainerAware as ContainerAwareContract;
use App\Support\Traits\Controller\ContainerAware;
use App\Support\Traits\Controller\CreateResponse;
use App\Support\Traits\Controller\RenderView;
use Aura\Web\Response;

/**
 * Class BaseController
 *
 * @package    App\Controllers
 * @subpackage App\Controllers\BaseController
 */
abstract class BaseController implements ContainerAwareContract
{

    use ContainerAware;
    use CreateResponse;
    use RenderView;

    /**
     * Redirect to a given path
     *
     * @param string  $path
     * @param integer $code HTTP status code to use
     *
     * @return Response
     */
    protected function redirect($path, $code = 302)
    {
        $response = $this->response();
        $response->redirect->to($path, $code);

        return $response;
    }
}
