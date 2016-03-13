<?php

namespace App\Contracts\Entity;

/**
 * Interface Blamable
 *
 * @package    App\Contracts\Entity
 * @subpackage App\Contracts\Entity\Blamable
 */
interface Blamable
{

    /**
     * @return string
     */
    public function getCreatedBy();

    /**
     * @param string $created_by
     *
     * @return Blamable
     */
    public function setCreatedBy($created_by);
}
