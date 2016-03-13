<?php

namespace App\Entities;

use App\Contracts\Entity\Identifiable as IdentifiableContract;
use App\Support\Traits\Entity\Identifiable;

/**
 * Class User
 *
 * @package    App\Entities
 * @subpackage App\Entities\User
 */
class User implements IdentifiableContract
{

    use Identifiable;

    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $password;



    /**
     * Constructor.
     *
     * @param null|string $username
     * @param null|string $email
     * @param null|string $password
     */
    public function __construct($username = null, $email = null, $password = null)
    {
        $this->username = $username;
        $this->email    = $email;
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }
}
