<?php

namespace App\Entities;

use App\Entities\Traits\Blamable;
use App\Entities\Traits\Identifiable;
use App\Entities\Traits\Timestampable;

/**
 * Class Comment
 *
 * @package    App\Entities
 * @subpackage App\Entities\Comment
 */
class Comment
{

    use Identifiable;
    use Blamable;
    use Timestampable;

    /**
     * @var string
     */
    protected $comment;

    /**
     * @var Story
     */
    protected $story;



    /**
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     *
     * @return $this
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * @return Story
     */
    public function getStory()
    {
        return $this->story;
    }

    /**
     * @param Story $story
     *
     * @return $this
     */
    public function setStory(Story $story)
    {
        $this->story = $story;

        return $this;
    }
}
