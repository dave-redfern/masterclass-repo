<?php

namespace App\Services\Validation;

use Aura\Filter\SubjectFilter;

/**
 * Class StoryValidator
 *
 * @package    App\Services\Validation
 * @subpackage App\Services\Validation\StoryValidator
 */
class StoryValidator extends SubjectFilter
{

    /**
     * Initialise filter rules
     */
    protected function init()
    {
        $this->sanitize('headline')->to('trim');
        $this->sanitize('headline')->to('callback', function ($subject, $field) {
            $subject->$field = strip_tags($subject->$field);
            return true;
        });
        $this->validate('headline')->isNotBlank();
        $this->validate('headline')->is('strlenBetween', 5, 500);

        $this->sanitize('url')->to('trim');
        $this->validate('url')->isNotBlank();
        $this->validate('url')->is('url');
        $this->validate('url')->is('strlenMax', 500);

        $this->useFieldMessage('headline', 'Please provide a headline that is no more than 500 characters.');
        $this->useFieldMessage('url',      'Please provide a valid url, no more than 500 characters.');
    }
}
