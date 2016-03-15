<?php

namespace App\Support\Traits\Controller;

use Aura\Di\ContainerInterface;

/**
 * Trait ContainerAware
 *
 * @package    App\Support\Traits\Controller
 * @subpackage App\Support\Traits\Controller\ContainerAware
 */
trait ContainerAware
{

    /**
     * @var ContainerInterface
     */
    protected $di;

    /**
     * @param ContainerInterface $di
     */
    public function setContainer(ContainerInterface $di)
    {
        $this->di = $di;
    }

    /**
     * @param string $service
     *
     * @return object
     * @throws \Aura\Di\Exception\ServiceNotFound
     */
    public function get($service)
    {
        return $this->di->get($service);
    }
}
