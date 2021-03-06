<?php

namespace App\Support\Traits\Controller;

use App\Services\Auth\Authenticator;

/**
 * Trait Authenticatable
 *
 * @package    App\Support\Traits\Controller
 * @subpackage App\Support\Traits\Controller\Authenticatable
 */
trait Authenticatable
{

    /**
     * @var Authenticator
     */
    protected $auth;

    /**
     * Checks if the current user is authenticated
     *
     * @param string $redirect
     *
     * @return boolean
     */
    public function isAuthenticated($redirect = '/')
    {
        if (!$this->auth->user()) {
            $this->redirect($redirect);
        }

        return true;
    }
}
