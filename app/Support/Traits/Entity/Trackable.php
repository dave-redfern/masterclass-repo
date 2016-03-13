<?php

namespace App\Support\Traits\Entity;

/**
 * Trait Trackable
 *
 * @package    App\Support\Traits\Entity
 * @subpackage App\Support\Traits\Entity\Trackable
 */
trait Trackable
{

    use Identifiable;
    use Blamable;
    use Timestampable;

}
