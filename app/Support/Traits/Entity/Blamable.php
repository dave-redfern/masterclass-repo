<?php

namespace App\Support\Traits\Entity;

/**
 * Trait Blamable
 *
 * @package    App\Support\Traits\Entity
 * @subpackage App\Support\Traits\Entity\Blamable
 */
trait Blamable
{

    /**
     * @var string
     */
    protected $created_by;

    /**
     * @return string
     */
    public function getCreatedBy()
    {
        return $this->created_by;
    }

    /**
     * @param string $created_by
     *
     * @return Blamable
     */
    public function setCreatedBy($created_by)
    {
        $this->created_by = $created_by;

        return $this;
    }
}
