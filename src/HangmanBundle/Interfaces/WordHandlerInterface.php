<?php

namespace HangmanBundle\Interfaces;

/**
 * Interface WordHandlerInterface
 *
 * @package HangmanBundle\Interfaces
 */
interface WordHandlerInterface
{
    /**
     * @param string $word
     */
    public function setWord($word);

    /**
     * @param array $guesses
     */
    public function setValidGuesses(array $guesses);

    /**
     * @param array $guesses
     */
    public function setInvalidGuesses(array $guesses);

    /**
     * @return array
     */
    public function getValidGuesses();

    /**
     * @return array
     */
    public function getInvalidGuesses();

    /**
     * @return string
     */
    public function getWord();

    /**
     * @return array
     */
    public function getGuesses();

    public function resetGame();
}
