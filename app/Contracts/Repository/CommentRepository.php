<?php

namespace App\Contracts\Repository;

use App\Entities\Comment;
use App\Entities\Story;
use App\Services\Repository\RepositoryInterface;
use App\Support\Collection;

/**
 * Interface CommentRepository
 *
 * @package    App\Contracts\Repository
 * @subpackage App\Contracts\Repository\CommentRepository
 */
interface CommentRepository extends RepositoryInterface
{

    /**
     * @param Story $story
     *
     * @return int
     */
    public function countForStory(Story $story);

    /**
     * @param Story $story
     * @param int   $offset
     * @param int   $limit
     *
     * @return Collection|Comment[]
     */
    public function findForStory(Story $story, $offset = null, $limit = null);
}
