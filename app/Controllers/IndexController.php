<?php

namespace App\Controllers;

use App\Repositories\CommentRepository;
use App\Repositories\StoryRepository;
use Aura\Web\Response;

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
     * @return Response
     */
    public function index()
    {
        $stories = $this->stories->findStoriesWithCommentCounts();

        return $this->view('index/index.twig', ['stories' => $stories]);
    }
}

