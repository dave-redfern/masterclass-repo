<?php

namespace App\Entities;

use App\Contracts\Entity\Trackable as TrackableContract;
use App\Support\Traits\Entity\Trackable;

/**
 * Class Comment
 *
 * @package    App\Entities
 * @subpackage App\Entities\Comment
 */
class Comment implements TrackableContract
{

    use Trackable;

    /**
     * @var string
     */
    protected $comment;

    /**
     * @var Story
     */
    protected $story;



    /**
     * Constructor.
     *
     * @param null|Story  $story
     * @param null|string $comment
     * @param null|string $createdBy
     * @param null|string $createdOn
     */
    public function __construct(Story $story = null, $comment = null, $createdBy = null, $createdOn = null)
    {
        $this->story      = $story;
        $this->comment    = $comment;
        $this->created_by = $createdBy;
        $this->created_on = $createdOn;
    }

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
