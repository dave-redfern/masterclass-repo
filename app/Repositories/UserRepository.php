<?php

namespace App\Repositories;

use App\Contracts\Repository\UserRepository as UserRepositoryContract;
use App\Entities\User;
use App\Services\Repository\EntityRepository;

/**
 * Class UserRepository
 *
 * @package    App\Repositories
 * @subpackage App\Repositories\UserRepository
 */
class UserRepository extends EntityRepository implements UserRepositoryContract
{

    /**
     * @param string $username
     *
     * @return null|User
     */
    public function findByUsername($username)
    {
        return $this->findOneBy(['username' => $username]);
    }
}
