<?php

namespace App\Contracts;

/**
 * Interface ViewRenderer
 *
 * @package    App\Contracts
 * @subpackage App\Contracts\ViewRenderer
 */
interface ViewRenderer
{

    /**
     * Render a template and return the resulting content
     *
     * @param string $template
     * @param array  $data
     *
     * @return string
     */
    public function render($template, array $data = []);
}
