<?php

namespace App\Services\Validation;

use App\Services\Config\Config;
use App\Support\Collection;
use Aura\Filter\Failure\FailureCollection;
use Aura\Filter\FilterFactory;

/**
 * Class Validator
 *
 * @package    App\Services\Validation
 * @subpackage App\Services\Validation\Validator
 */
class Validator
{

    /**
     * @var FilterFactory
     */
    protected $factory;

    /**
     * @var Collection
     */
    protected $validations;

    /**
     * @var FailureCollection
     */
    protected $failures;



    /**
     * Constructor.
     *
     * @param FilterFactory $factory
     * @param Config        $config
     */
    public function __construct(FilterFactory $factory, Config $config)
    {
        $this->factory     = $factory;
        $this->validations = new Collection($config->get('validators'));
    }

    /**
     * Validates an entity
     *
     * @param mixed $entity
     *
     * @return bool
     */
    public function validate($entity)
    {
        $this->failures = null;

        if (!is_object($entity)) {
            throw new \InvalidArgumentException(sprintf('Unable to validate, no object provided'));
        }
        if (null === $validator = $this->validations->get(get_class($entity))) {
            throw new \RuntimeException(sprintf('No validator found for "%s"', get_class($entity)));
        }

        $filter = $this->factory->newSubjectFilter($validator);
        $props  = $this->getEntityProperties($entity);

        if ($filter->apply($props)) {
            return true;
        }

        $this->failures = $filter->getFailures();

        return false;
    }

    /**
     * @return FailureCollection
     */
    public function getFailures()
    {
        return $this->failures;
    }

    /**
     * Aura filter does not like objects with protected properties and get methods :(
     *
     * @param object $entity
     *
     * @return array
     */
    protected function getEntityProperties($entity)
    {
        $validate      = [];
        $reflectObject = new \ReflectionObject($entity);

        foreach ($reflectObject->getProperties() as $property) {
            $property->setAccessible(true);

            $validate[$property->getName()] = $property->getValue($entity);
        }

        return $validate;
    }
}
