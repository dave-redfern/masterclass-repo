<?php

namespace App\Services\Validation\Filters;

use App\Repositories\UserRepository;

/**
 * Class UniqueUsername
 *
 * @package    App\Services\Validation\Filters
 * @subpackage App\Services\Validation\Filters\UniqueUsername
 */
class UniqueUsername
{

    /**
     * @var UserRepository
     */
    protected $users;



    /**
     * Constructor.
     *
     * @param UserRepository $users
     */
    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    /**
     * Validates that the username is unique
     *
     * @param $subject
     * @param $field
     *
     * @return bool
     */
    public function __invoke($subject, $field)
    {
        return (null === $this->users->findByUsername($subject->$field));
    }
}
