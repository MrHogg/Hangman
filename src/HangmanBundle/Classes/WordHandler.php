<?php

namespace HangmanBundle\Classes;

use HangmanBundle\Interfaces\WordHandlerInterface;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class WordHandler
 *
 * @package HangmanBundle\Classes
 */
class WordHandler implements WordHandlerInterface
{
    /** @var Session */
    protected $session;

    /**
     * @param Session $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
    }
    /**
     * @param string $word
     */
    public function setWord($word)
    {
        $this->session->set('hangman/word', $word);
    }

    /**
     * @param array $guesses
     */
    public function setValidGuesses(array $guesses)
    {
        $this->session->set('hangman/guesses/valid', $guesses);
    }

    /**
     * @param array $guesses
     */
    public function setInvalidGuesses(array $guesses)
    {
        $this->session->set('hangman/guesses/invalid', $guesses);
    }

    /**
     * @return array
     */
    public function getValidGuesses()
    {
        return $this->session->get('hangman/guesses/valid', array());
    }

    /**
     * @return array
     */
    public function getInvalidGuesses()
    {
        return $this->session->get('hangman/guesses/invalid', array());
    }

    /**
     * @return string
     */
    public function getWord()
    {
        return $this->session->get('hangman/word');
    }

    /**
     * @return array
     */
    public function getGuesses()
    {
        $guesses = $this->session->get('hangman/guesses', array());

        if (!isset($guesses['valid'])) {
            $guesses['valid'] = array();
        }

        if (!isset($guesses['invalid'])) {
            $guesses['invalid'] = array();
        }

        return $guesses;
    }

    public function resetGame()
    {
        $this->session->remove('hangman');
    }
}
