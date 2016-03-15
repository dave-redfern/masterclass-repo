<?php

namespace App\Services\View;

use App\Contracts\ViewRenderer;
use App\Services\Auth\Authenticator;

/**
 * Class TwigBridge
 *
 * @package    App\Services\View
 * @subpackage App\Services\View\TwigBridge
 */
class TwigBridge implements ViewRenderer
{

    /**
     * @var \Twig_Environment
     */
    protected $twig;

    /**
     * @var Authenticator
     */
    protected $auth;



    /**
     * Constructor.
     *
     * @param \Twig_Environment $twig
     * @param Authenticator     $auth
     */
    public function __construct(\Twig_Environment $twig, Authenticator $auth)
    {
        $this->twig = $twig;
        $this->auth = $auth;
    }

    /**
     * Render a template and return the resulting content
     *
     * @param string $template
     * @param array  $data
     *
     * @return string
     */
    public function render($template, array $data = [])
    {
        $this->assignGlobals();

        return $this->twig->render($template, $data);
    }

    /**
     * Assign global shared variables to view
     *
     * @return void
     */
    protected function assignGlobals()
    {
        $this->twig->addGlobal('current_user', $this->auth->user());
    }
}
