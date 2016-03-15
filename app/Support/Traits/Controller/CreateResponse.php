<?php

namespace App\Support\Traits\Controller;

use Aura\Web\Response;

/**
 * Trait CreateResponse
 *
 * @package    App\Support\Traits\Controller
 * @subpackage App\Support\Traits\Controller\CreateResponse
 */
trait CreateResponse
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
    protected function response()
    {
        return $this->get('response');
    }
}
