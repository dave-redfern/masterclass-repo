<?php

namespace App\Support\Traits\Entity;

/**
 * Trait Identifiable
 *
 * @package    App\Support\Traits\Entity
 * @subpackage App\Support\Traits\Entity\Identifiable
 */
trait Identifiable
{

    /**
     * @var integer
     */
    protected $id;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}
