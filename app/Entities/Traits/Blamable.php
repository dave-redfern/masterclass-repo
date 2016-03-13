<?php

namespace App\Entities\Traits;

/**
 * Trait Blamable
 *
 * @package    App\Entities\Traits
 * @subpackage App\Entities\Traits\Blamable
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
