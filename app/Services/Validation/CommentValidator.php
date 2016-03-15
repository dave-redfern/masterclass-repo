<?php

namespace App\Services\Validation;

use Aura\Filter\SubjectFilter;

/**
 * Class CommentValidator
 *
 * @package    App\Services\Validation
 * @subpackage App\Services\Validation\CommentValidator
 */
class CommentValidator extends SubjectFilter
{

    /**
     * Initialise filter rules
     */
    protected function init()
    {
        $this->sanitize('comment')->to('trim');
        $this->sanitize('comment')->to('callback', function ($subject, $field) {
            $subject->$field = strip_tags($subject->$field);
            return true;
        });

        $this->validate('comment')->isNotBlank();
        $this->validate('comment')->is('strlenBetween', 10, 32000);

        $this->useFieldMessage('comment', 'Please supply a comment at least 10 characters long.');
    }
}
