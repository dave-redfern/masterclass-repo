<?php

namespace App\Controllers;

use App\Repositories\UserRepository;
use App\Services\Auth\Authenticator;
use App\Services\Factory\EntityFactory;
use App\Services\Validation\Validator;
use App\Support\Http\Request;
use App\Support\Traits\Controller\Authenticatable;
use Aura\Web\Response;

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
     *
     * @return Response
     */
    public function create(EntityFactory $factory, Request $request)
    {
        $user   = $factory->createUser();
        $errors = [];

        // Do the create
        if ($request->input('create')) {
            /** @var Validator $validator */
            $validator = $this->get('validator');

            $user
                ->setUsername($request->input('username'))
                ->setEmail($request->input('email'))
                ->setPassword($request->input('password'))
                ->setPasswordCheck($request->input('password_check'))
            ;

            if ($validator->validate($user)) {
                $this->users->getPersister()->save($user);

                return $this->redirect('/user/login');
            }

            $errors = $validator->getFailures()->getMessages();
        }

        return $this->view('user/create.twig', [
            'errors' => $errors,
            'user'   => $user,
        ]);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function account(Request $request)
    {
        $user    = $this->auth->user();
        $success = null;
        $errors  = [];

        if ($request->input('updatepw')) {
            /** @var Validator $validator */
            $validator = $this->get('validator');

            $user
                ->setPassword($request->input('password'))
                ->setPasswordCheck($request->input('password_check'))
            ;

            if (!$validator->validate($user)) {
                $errors = $validator->getFailures()->getMessages();
            }

            $this->users->getPersister()->update($user);
            $success = 'Your password was changed.';
        }

        return $this->view('user/update.twig', [
            'errors'  => $errors,
            'success' => $success,
        ]);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function login(Request $request)
    {
        $error = null;

        if ($request->input('login')) {
            if ($this->auth->attempt($request->input('user'), $request->input('pass'))) {
                return $this->redirect('/');
            }

            $error = 'Your username/password did not match.';
        }

        return $this->view('user/login.twig', ['error' => $error]);
    }

    /**
     * Log out the user
     *
     * @return Response
     */
    public function logout()
    {
        $this->auth->logout();

        return $this->redirect('/');
    }
}
