<?php

namespace App\Entities;

use App\Entities\Traits\Blamable;
use App\Entities\Traits\Identifiable;
use App\Entities\Traits\Timestampable;

/**
 * Class Story
 *
 * @package    App\Entities
 * @subpackage App\Entities\Story
 */
class Story
{

    use Identifiable;
    use Blamable;
    use Timestampable;

    /**
     * @var string
     */
    protected $headline;

    /**
     * @var string
     */
    protected $url;



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
}
