<?php

namespace App\Entities\Traits;

/**
 * Trait Identifiable
 *
 * @package    App\Entities\Traits
 * @subpackage App\Entities\Traits\Identifiable
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
