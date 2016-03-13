<?php

namespace App\Services\Persister;

/**
 * Interface PersisterInterface
 *
 * @package    App\Services\Persister
 * @subpackage App\Services\Persister\PersisterInterface
 */
interface PersisterInterface
{

    /**
     * @param mixed $entity
     *
     * @return boolean
     */
    public function save($entity);

    /**
     * @param mixed $entity
     *
     * @return boolean
     */
    public function update($entity);

    /**
     * @param mixed $entity
     *
     * @return boolean
     */
    public function destroy($entity);

}
