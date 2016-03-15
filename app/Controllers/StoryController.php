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
 * Class StoryController
 *
 * @package    App\Controllers
 * @subpackage App\Controllers\StoryController
 */
class StoryController extends BaseController
{

    use Authenticatable;

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
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        if (!$id = $request->getQuery()->get('id')) {
            return $this->redirect('/');
        }
        /** @var Story $story */
        if (null === $story = $this->stories->find($id)) {
            return $this->redirect('/');
        }

        $comments = $this->comments->findForStory($story);
        $story->setCommentCount($comments->count());

        return $this->view('story/show.twig', [
            'story' => $story,
            'comments' => $comments,
        ]);
    }

    /**
     * @param EntityFactory $factory
     * @param Request       $request
     *
     * @return Response
     */
    public function create(EntityFactory $factory, Request $request)
    {
        $this->isAuthenticated();

        $errors = [];

        if ($request->input('create')) {
            $story     = $factory->createStory($request->input('headline'), $request->input('url'));
            /** @var Validator $validator */
            $validator = $this->get('validator');

            if ($validator->validate($story)) {
                $this->stories->getPersister()->save($story);

                return $this->redirect('/story/?id=' . $story->getId());
            }

            $errors = $validator->getFailures()->getMessages();
        }

        return $this->view('story/create.twig', [
            'errors' => $errors,
        ]);
    }

}
