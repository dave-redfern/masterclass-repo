<?php

namespace App\Support\Http;

use App\Support\Collection;

/**
 * Class Session
 *
 * @package    App\Support\Http
 * @subpackage App\Support\Http\Session
 */
class Session extends Collection
{

    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct($_SESSION);

        register_shutdown_function([$this, 'save']);
    }

    /**
     * @param bool $delete
     *
     * @return bool
     */
    public function migrate($delete = true)
    {
        return session_regenerate_id($delete);
    }

    /**
     * @return string
     */
    public function getId()
    {
        return session_id();
    }

    /**
     * @return bool
     */
    public function save()
    {
        $_SESSION = $this->collection;

        return true;
    }

    /**
     * @return boolean
     */
    public function start()
    {
        return session_start();
    }

    /**
     * @return boolean
     */
    public function destroy()
    {
        $this->collection->clear();

        return session_destroy();
    }
}
