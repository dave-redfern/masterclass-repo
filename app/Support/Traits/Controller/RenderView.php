<?php

namespace App\Support\Traits\Controller;

use Aura\Web\Response;

/**
 * Trait RenderView
 *
 * @package    App\Support\Traits\Controller
 * @subpackage App\Support\Traits\Controller\RenderView
 */
trait RenderView
{

    /**
     * @param string $service
     *
     * @return object
     */
    abstract protected function get($service);

    /**
     * @return Response
     */
    abstract protected function response();

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
}
