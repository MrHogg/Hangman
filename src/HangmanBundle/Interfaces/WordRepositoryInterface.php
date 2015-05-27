<?php

namespace HangmanBundle\Interfaces;

/**
 * Interface WordRepositoryInterface
 *
 * @package HangmanBundle\Interfaces
 */
interface WordRepositoryInterface
{
    /**
     * @return string
     */
    public function getRandomWord();
}
