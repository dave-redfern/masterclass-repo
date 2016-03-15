<?php

namespace App\Contracts\Controllers;

use Aura\Di\ContainerInterface;

/**
 * Interface ContainerAware
 *
 * @package    App\Contracts\Controllers
 * @subpackage App\Contracts\Controllers\ContainerAware
 */
interface ContainerAware
{

    /**
     * @param ContainerInterface $di
     *
     * @return void
     */
    public function setContainer(ContainerInterface $di);
}
