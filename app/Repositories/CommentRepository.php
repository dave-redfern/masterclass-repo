<?php

namespace App\Repositories;

use App\Entities\Story;

/**
 * Class CommentRepository
 *
 * @package    App\Repositories
 * @subpackage App\Repositories\CommentRepository
 */
class CommentRepository extends EntityRepository
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
}
