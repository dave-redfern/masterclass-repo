<?php

namespace App\Services\Repository;

use App\Support\Collection;

/**
 * Interface RepositoryInterface
 *
 * @package    App\Services\Repository
 * @subpackage App\Services\Repository\RepositoryInterface
 */
interface RepositoryInterface
{

    /**
     * @param array $where
     *
     * @return integer
     */
    public function count(array $where = []);

    /**
     * Fetches an instance of the object matching id
     *
     * @param integer $id
     *
     * @return null|object
     */
    public function find($id);

    /**
     * Finds all instances of the objects
     *
     * @return Collection
     */
    public function findAll();

    /**
     * @param array    $where
     * @param array    $orderBy
     * @param null|int $offset
     * @param null|int $limit
     *
     * @return Collection
     */
    public function findBy(array $where, array $orderBy = [], $offset = null, $limit = null);

    /**
     * @param array    $where
     * @param array    $orderBy
     *
     * @return null|object
     */
    public function findOneBy(array $where, array $orderBy = []);
}
