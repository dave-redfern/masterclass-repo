<?php

namespace App\Services;

use App\Request;
use App\Services\Config\Config;

/**
 * Class Router
 *
 * @package    App\Services
 * @subpackage App\Services\Router
 */
class Router
{

    /**
     * @var Config
     */
    protected $config;

    /**
     * Constructor.
     *
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @param Request $request
     *
     * @return Route|null
     */
    public function route(Request $request)
    {
        $rb     = $request->getServer()->get('REDIRECT_BASE', '');
        $ruri   = $request->getServer()->get('REQUEST_URI');
        $path   = str_replace($rb, '', $ruri);
        $return = null;

        foreach ($this->config['routes'] as $k => $v) {
            $matches = [];
            $pattern = '$' . $k . '$';

            if (preg_match($pattern, $path, $matches)) {
                list($controller, $method) = explode('@', $v);

                $return = new Route([
                    'path'       => array_shift($matches),
                    'controller' => $controller,
                    'method'     => $method,
                    'arguments'  => $matches,
                ]);
            }
        }

        return $return;
    }
}
