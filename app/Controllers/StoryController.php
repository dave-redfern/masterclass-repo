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
     */
    public function index(Request $request)
    {
        if (!$id = $request->getQuery()->get('id')) {
            $this->redirect('/');
        }
        /** @var Story $story */
        if (null === $story = $this->stories->find($id)) {
            $this->redirect('/');
        }

        $comment_count = $this->comments->countForStory($story);
        $comments      = $this->comments->findForStory($story);

        $content = '
            <a class="headline" href="' . $story->getUrl() . '">' . $story->getHeadline() . '</a><br />
            <span class="details">' . $story->getCreatedBy() . ' | ' . $comment_count . ' Comments |
            ' . $story->getCreatedOn()->format('n/j/Y g:i a') . '</span>
        ';

        if ($this->auth->user()) {
            $content .= '
            <form method="post" action="/comment/create">
            <input type="hidden" name="story_id" value="' . $story->getId() . '" />
            <textarea cols="60" rows="6" name="comment"></textarea><br />
            <input type="submit" name="submit" value="Submit Comment" />
            </form>            
            ';
        }

        foreach ($comments as $comment) {
            $content .= '
                <div class="comment"><span class="comment_details">' . $comment->getCreatedBy() . ' | ' .
                        $comment->getCreatedOn()->format('n/j/Y g:i a') . '</span>
                ' . $comment->getComment() . '</div>
            ';
        }

        require __DIR__ . '/../Resources/views/layout.phtml';

    }

    /**
     * @param EntityFactory $factory
     * @param Request       $request
     */
    public function create(EntityFactory $factory, Request $request)
    {
        $this->isAuthenticated();

        $error = '';

        if ($request->input('create')) {
            if (!$request->input('headline') || !$request->input('url') ||
                !filter_var($request->input('url'), FILTER_VALIDATE_URL)
            ) {
                $error = 'You did not fill in all the fields or the URL did not validate.';
            } else {
                $story = $factory->createStory($request->input('headline'), $request->input('url'));

                $this->stories->getPersister()->save($story);

                $this->redirect('/story/?id=' . $story->getId());
            }
        }

        $content = '
            <form method="post">
                ' . $error . '<br />
        
                <label>Headline:</label> <input type="text" name="headline" value="" /> <br />
                <label>URL:</label> <input type="text" name="url" value="" /><br />
                <input type="submit" name="create" value="Create" />
            </form>
        ';

        require __DIR__ . '/../Resources/views/layout.phtml';
    }

}
