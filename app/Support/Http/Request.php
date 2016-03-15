<?php

namespace App\Support\Http;

use App\Support\Collection;

/**
 * Class Request
 *
 * @package    App\Support\Http
 * @subpackage App\Support\Http\Request
 */
class Request
{

    /**
     * @var Collection
     */
    protected $query;

    /**
     * @var Collection
     */
    protected $request;

    /**
     * @var Collection
     */
    protected $server;

    /**
     * @var Session
     */
    protected $session;

    /**
     * @var string
     */
    protected $requestPath;



    /**
     * Constructor.
     *
     * @param array $query
     * @param array $request
     * @param array $server
     */
    public function __construct(array $query = null, array $request = null, array $server = null)
    {
        $this->query   = new Collection($query   ?: $_GET);
        $this->request = new Collection($request ?: $_POST);
        $this->server  = new Collection($server  ?: $_SERVER);
    }

    /**
     * @return static
     */
    public static function newFromGlobals()
    {
        return new static($_GET, $_POST, $_SERVER);
    }

    /**
     * @return Collection
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @return Collection
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return Collection
     */
    public function getServer()
    {
        return $this->server;
    }

    /**
     * @return Session
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * @param Session $session
     *
     * @return $this
     */
    public function setSession(Session $session)
    {
        $this->session = $session;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getRedirectBase()
    {
        return $this->getServer()->get('REDIRECT_BASE');
    }

    /**
     * @return string|null
     */
    public function getRequestMethod()
    {
        return $this->getServer()->get('REQUEST_METHOD');
    }

    /**
     * @return string|null
     */
    public function getRequestUri()
    {
        return $this->getServer()->get('REQUEST_URI');
    }

    /**
     * @return string
     */
    public function getRequestPath()
    {
        if (!$this->requestPath) {
            $this->requestPath = $this->prepareRequestPath();
        }

        return $this->requestPath;
    }

    /**
     * @return array
     */
    public function getRequestArguments()
    {
        $args = [];
        parse_str(parse_url($this->getRequestUri(), PHP_URL_QUERY), $args);

        return $args;
    }

    /**
     * Checks in Query and Request for $name, returning default if not found
     *
     * @param string $name
     * @param mixed  $default
     *
     * @return mixed|null
     */
    public function input($name, $default = null)
    {
        if (null !== $var = $this->getQuery()->get($name, null)) {
            return $var;
        }
        if (null !== $var = $this->getRequest()->get($name, null)) {
            return $var;
        }

        return $default;
    }



    /**
     * Prepares the path info.
     *
     * @return string path info
     */
    protected function prepareRequestPath()
    {
        if (null === ($requestUri = $this->getRequestUri())) {
            return '/';
        }

        // Remove the query string from REQUEST_URI
        if ($pos = strpos($requestUri, '?')) {
            $requestUri = substr($requestUri, 0, $pos);
        }

        if (false === $requestUri || '' === $requestUri) {
            return '/';
        }

        return (string) $requestUri;
    }
}
