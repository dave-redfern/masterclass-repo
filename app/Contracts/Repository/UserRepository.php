<?php

namespace App\Contracts\Repository;

use App\Entities\User;
use App\Services\Repository\RepositoryInterface;

/**
 * Interface UserRepository
 *
 * @package    App\Contracts\Repository
 * @subpackage App\Contracts\Repository\UserRepository
 */
interface UserRepository extends RepositoryInterface
{

    /**
     * @param string $username
     *
     * @return null|User
     */
    public function findByUsername($username);
}
