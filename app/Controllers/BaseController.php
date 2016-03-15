<?php

namespace App\Controllers;

use App\Contracts\Controllers\ContainerAware as ContainerAwareContract;
use App\Support\Traits\Controller\ContainerAware;
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

    /**
     * @return Response
     */
    protected function response()
    {
        return $this->get('response');
    }

    /**
     * Renders the template, returning the content
     *
     * @param string $template
     * @param array  $data
     *
     * @return Response
     */
    protected function view($template, array $data = [])
    {
        $result = $this->get('view')->render($template, $data);

        $response = $this->response();
        $response->content->set($result);
        $response->content->setCharset('utf8');
        $response->content->setType('text/html');
        $response->status->setCode(200);

        return $response;
    }

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
