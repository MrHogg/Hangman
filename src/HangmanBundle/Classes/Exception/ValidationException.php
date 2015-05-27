<?php

namespace HangmanBundle\Classes\Exception;

use Exception;

/**
 * Class ValidationException
 *
 * @package HangmanBundle\Classes\Exception
 */
class ValidationException extends Exception
{
    public function __construct()
    {
        parent::__construct('Invalid submission.');
    }
}
