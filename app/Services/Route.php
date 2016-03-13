<?php

namespace App\Services;

use App\Support\Collection;

/**
 * Class Route
 *
 * @package    App\Services
 * @subpackage App\Services\Route
 */
class Route extends Collection
{

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->get('path');
    }

    /**
     * @return string
     */
    public function getController()
    {
        return $this->get('controller');
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->get('method');
    }

    /**
     * @return array
     */
    public function getArguments()
    {
        return $this->get('arguments', []);
    }
}
