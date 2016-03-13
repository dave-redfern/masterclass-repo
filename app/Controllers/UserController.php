<?php

namespace App\Controllers;

use App\Repositories\UserRepository;
use App\Services\Auth\Authenticator;
use App\Services\Factory\EntityFactory;
use App\Support\Http\Request;
use App\Support\Traits\Controller\Authenticatable;

/**
 * Class UserController
 *
 * @package    App\Controllers
 * @subpackage App\Controllers\UserController
 */
class UserController extends BaseController
{

    use Authenticatable;

    /**
     * @var Authenticator
     */
    protected $auth;

    /**
     * @var UserRepository
     */
    protected $users;

    /**
     * Constructor.
     *
     * @param Authenticator  $auth
     * @param UserRepository $users
     */
    public function __construct(Authenticator $auth, UserRepository $users)
    {
        $this->auth  = $auth;
        $this->users = $users;
    }

    /**
     * @param EntityFactory $factory
     * @param Request       $request
     */
    public function create(EntityFactory $factory, Request $request)
    {
        $error = null;

        // Do the create
        if ($request->input('create')) {
            if (!$request->input('username') || !$request->input('email') ||
                !$request->input('password') || !$request->input('password_check')
            ) {
                $error = 'You did not fill in all required fields.';
            }

            if (is_null($error)) {
                if (!filter_var($request->input('email'), FILTER_VALIDATE_EMAIL)) {
                    $error = 'Your email address is invalid';
                }
            }

            if (is_null($error)) {
                if ($request->input('password') != $request->input('password_check')) {
                    $error = "Your passwords didn't match.";
                }
            }

            if (is_null($error)) {
                if (null !== $this->users->findByUsername($request->input('username'))) {
                    $error = 'Your chosen username already exists. Please choose another.';
                }
            }

            if (is_null($error)) {
                $user = $factory->createUser(
                    $request->input('username'),
                    $request->input('email'),
                    $request->input('password')
                );

                $this->users->getPersister()->save($user);

                $this->redirect('/user/login');
            }
        }
        // Show the create form

        $content = '
            <form method="post">
                ' . $error . '<br />
                <label>Username</label> <input type="text" name="username" value="" /><br />
                <label>Email</label> <input type="text" name="email" value="" /><br />
                <label>Password</label> <input type="password" name="password" value="" /><br />
                <label>Password Again</label> <input type="password" name="password_check" value="" /><br />
                <input type="submit" name="create" value="Create User" />
            </form>
        ';

        require __DIR__ . '/../Resources/views/layout.phtml';

    }

    /**
     * @param Request $request
     */
    public function account(Request $request)
    {
        $this->isAuthenticated('/user/login');

        $user  = $this->auth->user();
        $error = null;

        if ($request->input('updatepw')) {
            if (!$request->input('password') || !$request->input('password_check') ||
                $request->input('password') != $request->input('password_check')
            ) {
                $error = 'The password fields were blank or they did not match. Please try again.';
            } else {
                $user->setPassword(password_hash($request->input('password'), PASSWORD_BCRYPT));

                $this->users->getPersister()->update($user);

                $error = 'Your password was changed.';
            }
        }

        $content = '
        ' . $error . '<br />
        
        <label>Username:</label> ' . $user->getUsername() . '<br />
        <label>Email:</label>' . $user->getEmail() . ' <br />
        
         <form method="post">
                ' . $error . '<br />
            <label>Password</label> <input type="password" name="password" value="" /><br />
            <label>Password Again</label> <input type="password" name="password_check" value="" /><br />
            <input type="submit" name="updatepw" value="Update User" />
        </form>';

        require __DIR__ . '/../Resources/views/layout.phtml';
    }

    /**
     * @param Request $request
     */
    public function login(Request $request)
    {
        $error = null;

        if ($request->input('login')) {
            if ($this->auth->attempt($request->input('user'), $request->input('pass'))) {
                $this->redirect('/');
            }

            $error = 'Your username/password did not match.';
        }

        $content = '
            <form method="post">
                ' . $error . '<br />
                <label>Username</label> <input type="text" name="user" value="" />
                <label>Password</label> <input type="password" name="pass" value="" />
                <input type="submit" name="login" value="Log In" />
            </form>
        ';

        require __DIR__ . '/../Resources/views/layout.phtml';
    }

    /**
     * Log out the user
     */
    public function logout()
    {
        $this->auth->logout();

        $this->redirect('/');
    }
}
