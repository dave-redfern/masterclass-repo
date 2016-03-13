<?php

namespace App\Controllers;

use App\Repositories\CommentRepository;
use App\Repositories\StoryRepository;
use App\Request;
use PDO;

/**
 * Class StoryController
 *
 * @package    App\Controllers
 * @subpackage App\Controllers\StoryController
 */
class StoryController
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

    public function index(Request $request)
    {
        if (!$id = $request->getQuery()->get('id')) {
            header("Location: /");
            exit;
        }
        if (null === $story = $this->stories->find($id)) {
            header("Location: /");
            exit;
        }

        $comment_count = $this->comments->countForStory($story);
        $comments      = $this->comments->findBy(['story_id' => $story->getId()], ['created_on' => 'ASC']);

        $content = '
            <a class="headline" href="' . $story->getUrl() . '">' . $story->getHeadline() . '</a><br />
            <span class="details">' . $story->getCreatedBy() . ' | ' . $comment_count . ' Comments |
            ' . $story->getCreatedOn()->format('n/j/Y g:i a') . '</span>
        ';

        if (isset($_SESSION['AUTHENTICATED'])) {
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

    public function create()
    {
        if (!isset($_SESSION['AUTHENTICATED'])) {
            header("Location: /user/login");
            exit;
        }

        $error = '';
        if (isset($_POST['create'])) {
            if (!isset($_POST['headline']) || !isset($_POST['url']) ||
                !filter_input(INPUT_POST, 'url', FILTER_VALIDATE_URL)
            ) {
                $error = 'You did not fill in all the fields or the URL did not validate.';
            } else {
                $sql  = 'INSERT INTO story (headline, url, created_by, created_on) VALUES (?, ?, ?, NOW())';
                $stmt = $this->db->prepare($sql);
                $stmt->execute([
                    $_POST['headline'],
                    $_POST['url'],
                    $_SESSION['username'],
                ]);

                $id = $this->db->lastInsertId();
                header("Location: /story/?id=$id");
                exit;
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
