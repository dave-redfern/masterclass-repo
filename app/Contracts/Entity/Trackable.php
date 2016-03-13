<?php

namespace App\Contracts\Entity;

/**
 * Interface Trackable
 *
 * @package    App\Contracts\Entity
 * @subpackage App\Contracts\Entity\Trackable
 */
interface Trackable extends Identifiable, Blamable, Timestampable
{

}
