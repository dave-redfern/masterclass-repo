<?php

namespace App\Repositories;

use App\Contracts\Repository\StoryRepository as StoryRepositoryContract;
use App\Entities\Comment;
use App\Services\Repository\EntityRepository;

/**
 * Class StoryRepository
 *
 * @package    App\Repositories
 * @subpackage App\Repositories\StoryRepository
 */
class StoryRepository extends EntityRepository implements StoryRepositoryContract
{

    /**
     * Returns stories with comment counts
     *
     * @return \App\Support\Collection
     */
    public function findStoriesWithCommentCounts()
    {
        /** @var CommentRepository $comments */
        $comments = $this->getRepository(Comment::class);
        $stories = $this->findBy([], ['created_on' => 'DESC']);

        foreach ($stories as $story) {
            $story->setCommentCount($comments->countForStory($story));
        }

        return $stories;
    }
}
