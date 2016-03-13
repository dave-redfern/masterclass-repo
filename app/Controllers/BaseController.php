<?php

namespace App\Controllers;

/**
 * Class BaseController
 *
 * @package    App\Controllers
 * @subpackage App\Controllers\BaseController
 */
abstract class BaseController
{

    /**
     * Redirect to a given path
     *
     * @param string       $path
     * @param null|integer $code HTTP status code to use
     */
    public function redirect($path, $code = null)
    {
        header("Location: " . $path, true, $code);
        exit;
    }
}
