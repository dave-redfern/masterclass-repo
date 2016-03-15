<?php

namespace App\Entities;

use App\Contracts\Entity\Trackable as TrackableContract;
use App\Support\Traits\Entity\Trackable;

/**
 * Class Story
 *
 * @package    App\Entities
 * @subpackage App\Entities\Story
 */
class Story implements TrackableContract
{

    use Trackable;

    /**
     * @var string
     */
    protected $headline;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var integer
     */
    protected $commentCount = 0;



    /**
     * Constructor.
     *
     * @param string $headline
     * @param string $url
     * @param string $createdBy
     * @param string $createdOn (string or DateTime)
     */
    public function __construct($headline = null, $url = null, $createdBy = null, $createdOn = null)
    {
        $this->headline   = $headline;
        $this->url        = $url;
        $this->created_by = $createdBy;
        $this->created_on = $createdOn;
    }

    /**
     * @return string
     */
    public function getHeadline()
    {
        return $this->headline;
    }

    /**
     * @param string $headline
     *
     * @return Story
     */
    public function setHeadline($headline)
    {
        $this->headline = $headline;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return Story
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return int
     */
    public function getCommentCount()
    {
        return $this->commentCount;
    }

    /**
     * @param int $commentCount
     *
     * @return $this
     */
    public function setCommentCount($commentCount)
    {
        $this->commentCount = $commentCount;

        return $this;
    }
}
