<?php

namespace App\Persisters;

use App\Entities\Comment;
use App\Services\Persister\EntityPersister;

/**
 * Class CommentPersister
 *
 * @package    App\Persisters
 * @subpackage App\Persisters\CommentPersister
 */
class CommentPersister extends EntityPersister
{

    /**
     * @param Comment $entity
     *
     * @return boolean
     */
    protected function _save($entity)
    {
        $sql = sprintf('
            INSERT INTO %s
                (story_id, comment, created_by, created_on)
            VALUES
                (:story_id, :comment, :created_by, :created_on)
        ', $this->getMappedTable());

        $stmt = $this->connection->prepare($sql);
        $res  = $stmt->execute([
            ':story_id'   => $entity->getStory()->getId(),
            ':comment'    => $entity->getComment(),
            ':created_by' => $entity->getCreatedBy(),
            ':created_on' => (new \DateTime())->format('Y-m-d H:i:s'),
        ]);

        $this->updatePrimaryKey($entity, $this->connection->lastInsertId());

        return $res;
    }

    /**
     * @param Comment $entity
     *
     * @return boolean
     */
    protected function _update($entity)
    {
        $sql = sprintf('
            UPDATE %s
               SET comment = :comment
             WHERE id = :id
        ', $this->getMappedTable());

        $stmt = $this->connection->prepare($sql);

        return $stmt->execute([
            ':id'       => $entity->getId(),
            ':username' => $entity->getComment(),
        ]);
    }

    /**
     * @param Comment $entity
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
}
