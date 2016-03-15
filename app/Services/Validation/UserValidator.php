<?php

namespace App\Services\Validation;

use Aura\Filter\Spec\Spec;
use Aura\Filter\SubjectFilter;

/**
 * Class UserValidator
 *
 * @package    App\Services\Validation
 * @subpackage App\Services\Validation\UserValidator
 */
class UserValidator extends SubjectFilter
{

    /**
     * Initialise filter rules
     */
    protected function init()
    {
        $this->validate('username')->isNotBlank()->setMessage('Please provide a username.');
        $this->validate('username')->is('alnum')->setMessage('The username must be A-Z and 0-9 only');
        $this->validate('username')->is('strlenBetween', 5, 255)->setMessage('The username must be at least 5 characters long.');
        $this->validate('username')->is('uniqueUsername')->setMessage('Your username must be unique.');

        $this->validate('email')->isNotBlank()->setMessage('Please provide an email address.');
        $this->validate('email')->is('email')->setMessage('Please provide a valid email address');
        $this->validate('email')->is('strlenMax', 255)->setMessage('The email can be a maximum of 255 characters.');

        $this->validate('password')->isNotBlank()->setMessage('Please provide a password and confirm it.');
        $this->validate('password')->is('strlenBetween', 6, 128)->setMessage('The password should be at least 6 characters');
        $this->validate('password')->is('equalToField', 'password_check')->setMessage('Please confirm your password');
    }

    /**
     * Overriding to allow skipping username checks when object has been persisted already
     *
     * @inheritdoc
     */
    protected function applyToObject($object)
    {
        $this->skip = array();
        $this->failures = clone $this->proto_failures;

        /** @var Spec $spec */
        foreach ($this->specs as $spec) {
            if ($spec->getField() == 'username' && $object->id) {
                continue;
            }

            $continue = $this->applySpec($spec, $object);
            if (! $continue) {
                break;
            }
        }
        return $this->failures->isEmpty();
    }
}
