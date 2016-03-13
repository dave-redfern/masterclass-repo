<?php

namespace App\Controllers;

use App\Entities\Story;
use App\Repositories\CommentRepository;
use App\Repositories\StoryRepository;
use App\Services\Auth\Authenticator;
use App\Services\Factory\EntityFactory;
use App\Support\Http\Request;
use App\Support\Traits\Controller\Authenticatable;

/**
 * Class CommentController
 *
 * @package    App\Controllers
 * @subpackage App\Controllers\CommentController
 */
class CommentController extends BaseController
{

    use Authenticatable;

    /**
     * @var Authenticator
     */
    protected $auth;

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
     * @param Authenticator     $auth
     * @param StoryRepository   $stories
     * @param CommentRepository $comments
     */
    public function __construct(Authenticator $auth, StoryRepository $stories, CommentRepository $comments)
    {
        $this->auth     = $auth;
        $this->stories  = $stories;
        $this->comments = $comments;
    }

    /**
     * @param EntityFactory $factory
     * @param Request       $request
     *
     * @return void
     */
    public function create(EntityFactory $factory, Request $request)
    {
        $this->isAuthenticated();

        /** @var Story $story */
        if (null === $story = $this->stories->find($request->input('story_id'))) {
            $this->redirect('/');
        }
        if (!$request->input('comment')) {
            $this->redirect('/story/?id=' . $story->getId());
        }

        $comment = $factory->createComment($story, $request->input('comment'));

        $this->comments->getPersister()->save($comment);

        $this->redirect('/story/?id=' . $story->getId());
    }
}
