<?php

namespace App\Repositories;

use App\Entities\Comment;
use App\Entities\Story;
use App\Contracts\Repository\CommentRepository as CommentRepositoryContract;
use App\Services\Repository\EntityRepository;
use App\Support\Collection;

/**
 * Class CommentRepository
 *
 * @package    App\Repositories
 * @subpackage App\Repositories\CommentRepository
 */
class CommentRepository extends EntityRepository implements CommentRepositoryContract
{

    /**
     * @param Story $story
     *
     * @return int
     */
    public function countForStory(Story $story)
    {
        return $this->count(['story_id' => $story->getId()]);
    }

    /**
     * @param Story $story
     * @param int   $offset
     * @param int   $limit
     *
     * @return Collection|Comment[]
     */
    public function findForStory(Story $story, $offset = null, $limit = null)
    {
        return $this->findBy(['story_id' => $story->getId()], ['created_on' => 'DESC'], $offset, $limit);
    }
}
