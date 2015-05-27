<?php

namespace HangmanBundle\Classes\Exception;

use Exception;

/**
 * Class DuplicateGuessException
 *
 * @package HangmanBundle\Classes\Exception
 */
class DuplicateGuessException extends Exception
{
    public function __construct()
    {
        parent::__construct('Guess already attempted.');
    }
}
