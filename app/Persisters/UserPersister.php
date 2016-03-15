<?php

namespace App\Persisters;

use App\Entities\User;
use App\Services\Persister\EntityPersister;

/**
 * Class UserPersister
 *
 * @package    App\Persisters
 * @subpackage App\Persisters\UserPersister
 */
class UserPersister extends EntityPersister
{

    /**
     * @param User $entity
     *
     * @return boolean
     */
    protected function _save($entity)
    {
        $sql = sprintf('
            INSERT INTO %s
                (username, email, password)
            VALUES
                (:username, :email, :password)
        ', $this->getMappedTable());

        $this->ensurePasswordIsHashed($entity);

        $stmt = $this->connection->prepare($sql);
        $res  = $stmt->execute([
            ':username' => $entity->getUsername(),
            ':email'    => $entity->getEmail(),
            ':password' => $entity->getPassword(),
        ]);

        $this->updatePrimaryKey($entity, $this->connection->lastInsertId());

        return $res;
    }

    /**
     * @param User $entity
     *
     * @return boolean
     */
    protected function _update($entity)
    {
        $sql = sprintf('
            UPDATE %s
               SET username = :username,
                   email = :email,
                   password = :password
             WHERE id = :id
        ', $this->getMappedTable());

        $this->ensurePasswordIsHashed($entity);

        $stmt = $this->connection->prepare($sql);

        return $stmt->execute([
            ':id'       => $entity->getId(),
            ':username' => $entity->getUsername(),
            ':email'    => $entity->getEmail(),
            ':password' => $entity->getPassword(),
        ]);
    }

    /**
     * @param User $entity
     *
     * @return boolean
     */
    protected function _destroy($entity)
    {
        $sql = sprintf('
            DELETE FROM %s
             WHERE id = :id
        ', $this->getMappedTable());

        $stmt = $this->connection->prepare($sql);

        return $stmt->execute([
            ':id' => $entity->getId(),
        ]);
    }

    /**
     * @param User $entity
     */
    protected function ensurePasswordIsHashed(User $entity)
    {
        if (password_needs_rehash($entity->getPassword(), PASSWORD_BCRYPT)) {
            $entity->setPassword(password_hash($entity->getPassword(), PASSWORD_BCRYPT));
        }
    }
}
