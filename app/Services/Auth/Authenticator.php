<?php

namespace App\Services\Auth;

use App\Entities\User;
use App\Repositories\UserRepository;
use App\Support\Http\Request;

/**
 * Class Authenticator
 *
 * @package    App\Services\Auth
 * @subpackage App\Services\Auth\Authenticator
 */
class Authenticator
{

    /**
     * @var UserRepository
     */
    protected $users;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var null|User
     */
    protected $user;



    /**
     * Constructor.
     *
     * @param UserRepository $users
     * @param Request        $request
     */
    public function __construct(UserRepository $users, Request $request)
    {
        $this->users   = $users;
        $this->request = $request;
    }

    /**
     * Attempts to authenticate the user credentials and log them in
     *
     * @param string $username
     * @param string $password
     * @param bool   $login
     *
     * @return bool
     */
    public function attempt($username, $password, $login = true)
    {
        if (null !== $user = $this->authenticate($username, $password)) {
            if ($login) {
                $this->login($user);
            }

            return true;
        }

        return false;
    }

    /**
     * Login a user and register them with the session
     *
     * @param User $user
     *
     * @return User
     */
    public function login(User $user)
    {
        $session = $this->request->getSession();

        $session->migrate(true);
        $session
            ->set('AUTHENTICATED', true)
            ->set('username', $user->getUsername())
            ->set('user_id', $user->getId())
        ;

        return $this->user = $user;
    }

    /**
     * Log out the current user and destroy the session
     *
     * @return bool
     */
    public function logout()
    {
        $session = $this->request->getSession();
        $session->clear();
        $session->migrate(true);

        $this->user = null;

        return true;
    }

    /**
     * @return null|User
     */
    public function user()
    {
        if (is_null($this->user)) {
            $this->fetchAuthenticatedUser();
        }

        return $this->user;
    }

    /**
     * @param string  $username
     * @param string  $password
     *
     * @return User|null
     */
    protected function authenticate($username, $password)
    {
        if (null === $user = $this->users->findByUsername($username)) {
            return null;
        }

        if (!password_verify($password, $user->getPassword())) {
            return null;
        }

        return $user;
    }

    /**
     * @return User|null
     */
    protected function fetchAuthenticatedUser()
    {
        $session = $this->request->getSession();
        if ($session->has('username')) {
            $this->user = $this->users->findByUsername($session->get('username'));
        }

        return $this->user;
    }
}
