<?php

namespace App\Support\Http;

use App\Services\Auth\Authenticator;
use App\Services\Router\Route;
use Aura\Web\WebFactory;

/**
 * Class Auth
 *
 * @package    App\Support\Http
 * @subpackage App\Support\Http\Auth
 */
class Auth
{

    /**
     * @var Authenticator
     */
    protected $auth;

    /**
     * @var WebFactory
     */
    protected $factory;



    /**
     * Constructor.
     *
     * @param Authenticator $auth
     * @param WebFactory    $factory
     */
    public function __construct(Authenticator $auth, WebFactory $factory)
    {
        $this->auth    = $auth;
        $this->factory = $factory;
    }

    /**
     * @return \Aura\Web\Response|bool
     */
    public function authorize()
    {
        if ($this->auth->user()) {
            return true;
        }

        $response = $this->factory->newResponse();
        $response->redirect->temporaryRedirect('/user/login');

        return $response;
    }
}
