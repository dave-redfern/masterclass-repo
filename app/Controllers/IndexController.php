<?php

namespace App\Controllers;

use App\Repositories\CommentRepository;
use App\Repositories\StoryRepository;

/**
 * Class IndexController
 *
 * @package    App\Controllers
 * @subpackage App\Controllers\IndexController
 */
class IndexController extends BaseController
{

    /**
     * @var StoryRepository
     */
    protected $stories;

    /**
     * @var CommentRepository
     */
    protected $comments;

    /**
     * Constructor.
     *
     * @param StoryRepository   $stories
     * @param CommentRepository $comments
     */
    public function __construct(StoryRepository $stories, CommentRepository $comments)
    {
        $this->stories  = $stories;
        $this->comments = $comments;
    }

    /**
     * @return void
     */
    public function index()
    {
        $stories = $this->stories->findBy([], ['created_on' => 'DESC']);

        $content = '<ol>';

        foreach ($stories as $story) {
            $count = $this->comments->countForStory($story);

            $content .= '
                <li>
                <a class="headline" href="' . $story->getUrl() . '">' . $story->getHeadline() . '</a><br />
                <span class="details">' . $story->getCreatedBy() . ' | <a href="/story/?id=' . $story->getId() . '">' . $count . ' Comments</a> |
                ' . $story->getCreatedOn()->format('n/j/Y g:i a') . '</span>
                </li>
            ';
        }

        $content .= '</ol>';

        require __DIR__ . '/../Resources/views/layout.phtml';
    }
}

