<?php

namespace App\Contracts\Entity;

/**
 * Interface Timestampable
 *
 * @package    App\Contracts\Entity
 * @subpackage App\Contracts\Entity\Timestampable
 */
interface Timestampable
{

    /**
     * @return \DateTime
     */
    public function getCreatedOn();

    /**
     * @param \DateTime $created_on
     *
     * @return $this
     */
    public function setCreatedOn($created_on);
}
