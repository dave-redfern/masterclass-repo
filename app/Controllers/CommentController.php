<?php

namespace App\Controllers;

use App\Entities\Story;
use App\Repositories\CommentRepository;
use App\Repositories\StoryRepository;
use App\Services\Auth\Authenticator;
use App\Services\Factory\EntityFactory;
use App\Services\Validation\Validator;
use App\Support\Http\Request;
use App\Support\Traits\Controller\Authenticatable;
use Aura\Web\Response;

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
     * @return Response
     */
    public function create(EntityFactory $factory, Request $request)
    {
        /** @var Story $story */
        if (null === $story = $this->stories->find($request->input('story_id'))) {
            return $this->redirect('/');
        }

        $story->setCommentCount($this->comments->countForStory($story));
        $comment   = $factory->createComment($story, $request->input('comment'));
        /** @var Validator $validator */
        $validator = $this->get('validator');

        if ($validator->validate($comment)) {
            $this->comments->getPersister()->save($comment);

            return $this->redirect('/story/?id=' . $story->getId());
        }

        return $this->view('comment/create.twig', [
            'story' => $story,
            'error' => $validator->getFailures()->getMessagesForFieldAsString('comment'),
        ]);
    }
}
