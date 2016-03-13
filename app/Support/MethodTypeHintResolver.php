<?php

namespace App\Support;

use Aura\Di\Container;

/**
 * Class ArgumentTypeHintResolver
 *
 * @package    App\Support
 * @subpackage App\Support\ArgumentTypeHintResolver
 */
class MethodTypeHintResolver
{

    /**
     * @var Container
     */
    protected $di;

    /**
     * Constructor.
     *
     * @param Container $di
     */
    public function __construct(Container $di)
    {
        $this->di = $di;
    }

    /**
     * Creates an array of arguments for the method containing resolved object instances
     *
     * If an array of arguments is also passed, then provided these are indexed by the
     * argument name, they will also be added to the returned array of arguments.
     *
     * @param mixed  $class
     * @param string $method
     * @param array  $arguments (optional) Other arguments indexed by argument name
     *
     * @return array
     * @throws \Aura\Di\Exception\ServiceNotFound
     */
    public function resolveArguments($class, $method, array $arguments = [])
    {
        $reflect = new \ReflectionMethod($class, $method);
        $params  = $reflect->getParameters();
        $args    = [];

        foreach ($params as $param) {
            if ($param->getClass()) {
                $args[$param->getPosition()] = $this->di->get($param->getClass()->getName());
            } else {
                if (array_key_exists($param->getName(), $arguments)) {
                    $args[$param->getPosition()] = $arguments[$param->getName()];
                }
            }
        }

        return $args;
    }
}
