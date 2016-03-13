<?php

namespace App\Entities\Traits;

/**
 * Trait Timestampable
 *
 * @package    App\Entities\Traits
 * @subpackage App\Entities\Traits\Timestampable
 */
trait Timestampable
{

    /**
     * @var \DateTime
     */
    protected $created_on;

    /**
     * @return \DateTime
     */
    public function getCreatedOn()
    {
        $this->convertToDateTime($this->created_on);

        return $this->created_on;
    }

    /**
     * @param \DateTime $created_on
     *
     * @return $this
     */
    public function setCreatedOn($created_on)
    {
        $this->created_on = $this->convertToDateTime($created_on);

        return $this;
    }

    /**
     * @param mixed $date
     *
     * @return \DateTime
     */
    protected function convertToDateTime($date)
    {
        if ($date instanceof \DateTime) {
            return $date;
        }

        return $this->created_on = new \DateTime($date);
    }
}
