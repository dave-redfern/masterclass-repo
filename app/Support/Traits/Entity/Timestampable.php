<?php

namespace App\Support\Traits\Entity;

/**
 * Trait Timestampable
 *
 * @package    App\Support\Traits\Entity
 * @subpackage App\Support\Traits\Entity\Timestampable
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
