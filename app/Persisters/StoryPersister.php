<?php

namespace App\Persisters;

use App\Entities\Story;
use App\Services\Persister\EntityPersister;

/**
 * Class StoryPersister
 *
 * @package    App\Persisters
 * @subpackage App\Persisters\StoryPersister
 */
class StoryPersister extends EntityPersister
{

    /**
     * @param Story $entity
     *
     * @return boolean
     */
    protected function _save($entity)
    {
        $sql = sprintf('
            INSERT INTO %s
                (headline, url, created_by, created_on)
            VALUES
                (:headline, :url, :created_by, :created_on)
        ', $this->getMappedTable());

        $stmt = $this->connection->prepare($sql);
        $res  = $stmt->execute([
            ':headline'   => $entity->getHeadline(),
            ':url'        => $entity->getUrl(),
            ':created_by' => $entity->getCreatedBy(),
            ':created_on' => (new \DateTime())->format('Y-m-d H:i:s'),
        ]);

        $this->updatePrimaryKey($entity, $this->connection->lastInsertId());

        return $res;
    }

    /**
     * @param Story $entity
     *
     * @return boolean
     */
    protected function _update($entity)
    {
        $sql = sprintf('
            UPDATE %s
               SET url = :url,
                   headline = :headline
             WHERE id = :id
        ', $this->getMappedTable());

        $stmt = $this->connection->prepare($sql);

        return $stmt->execute([
            ':id'       => $entity->getId(),
            ':url'      => $entity->getUrl(),
            ':headline' => $entity->getHeadline(),
        ]);
    }

    /**
     * @param Story $entity
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
