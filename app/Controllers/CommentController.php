<?php

namespace App\Controllers;

use App\Repositories\CommentRepository;
use PDO;

/**
 * Class CommentController
 *
 * @package    App\Controllers
 * @subpackage App\Controllers\CommentController
 */
class CommentController
{

    /**
     * Constructor.
     *
     * @param CommentRepository $comments
     */
    public function __construct(CommentRepository $comments)
    {
        $this->comments = $comments;
    }

    public function create()
    {
        if (!isset($_SESSION['AUTHENTICATED'])) {
            die('not auth');
            header("Location: /");
            exit;
        }

        $sql  = 'INSERT INTO comment (created_by, created_on, story_id, comment) VALUES (?, NOW(), ?, ?)';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $_SESSION['username'],
            $_POST['story_id'],
            filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
        ]);
        header("Location: /story/?id=" . $_POST['story_id']);
    }

}
