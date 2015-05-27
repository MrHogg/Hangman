<?php

namespace HangmanBundle\Classes;

use HangmanBundle\Classes\Exception\DuplicateGuessException;
use HangmanBundle\Classes\Exception\InvalidGameException;
use HangmanBundle\Classes\Exception\ValidationException;
use HangmanBundle\Interfaces\WordHandlerInterface;
use HangmanBundle\Interfaces\WordRepositoryInterface;

/**
 * Class WordHelper
 *
 * @package HangmanBundle\Classes
 */
class WordHelper
{
    const LETTER_PLACEHOLDER = '_';
    const MAX_GUESSES = 7;

    /** @var WordRepositoryInterface */
    protected $wordRepository;

    /** @var WordHandlerInterface */
    protected $wordHandler;

    /** @var array */
    protected $validGuesses;

    /** @var array */
    protected $invalidGuesses;

    /**
     * @param WordRepositoryInterface $wordRepository
     * @param WordHandlerInterface    $wordHandler
     */
    public function __construct(
        WordRepositoryInterface $wordRepository,
        WordHandlerInterface    $wordHandler
    ) {
        $this->wordRepository = $wordRepository;
        $this->wordHandler = $wordHandler;

        $this->getGuesses();
    }

    public function generateWord()
    {
        $word = $this->wordRepository->getRandomWord();

        $this->wordHandler->setWord($word);
    }

    /**
     * @param string $letter
     *
     * @throws DuplicateGuessException
     *
     * @return boolean
     */
    public function guess($letter)
    {
        $this->validateGame(true);

        $this->validateLetter($letter);

        $this->validateGuess($letter);

        $successfulGuess = $this->isLetterInWord($letter);

        if ($successfulGuess) {
            $this->validGuesses[] = $letter;
            $this->wordHandler->setValidGuesses($this->validGuesses);
        } else {
            $this->invalidGuesses[] = $letter;
            $this->wordHandler->setInvalidGuesses($this->invalidGuesses);
        }

        return $successfulGuess;
    }

    /**
     * @param string $placeholder
     *
     * @return string
     */
    public function getRedactedWord($placeholder = self::LETTER_PLACEHOLDER)
    {
        $word = $this->wordHandler->getWord();

        $regex = sprintf('/[^%s ]/i', implode('', $this->validGuesses));

        return preg_replace($regex, $placeholder, $word);
    }

    /**
     * @return array
     */
    public function getData()
    {
        $word = $this->getRedactedWord();
        $guesses = $this->wordHandler->getGuesses();

        $data = array(
            'word'    => $word,
            'guesses' => $guesses,
            'won'     => $this->isGameWon(),
            'lost'    => $this->isGameLost(),
        );

        if ($data['won'] || $data['lost']) {
            $data['answer'] = $this->wordHandler->getWord();
        }

        return $data;
    }

    /**
     * @param string $letter
     *
     * @return boolean
     */
    protected function isLetterInWord($letter)
    {
        $word = $this->wordHandler->getWord();

        return stripos($word, $letter) !== false;
    }

    protected function getGuesses()
    {
        $this->invalidGuesses = $this->wordHandler->getInvalidGuesses();
        $this->validGuesses = $this->wordHandler->getValidGuesses();
    }

    /**
     * @return boolean
     */
    public function isGameWon()
    {
        return stripos($this->getRedactedWord(), self::LETTER_PLACEHOLDER) === false;
    }

    /**
     * @return boolean
     */
    public function isGameLost()
    {
        return count($this->wordHandler->getInvalidGuesses()) >= self::MAX_GUESSES;
    }

    /**
     * @throws InvalidGameException
     */
    protected function validateGuesses()
    {
        if (count($this->invalidGuesses) >= self::MAX_GUESSES) {
            throw new InvalidGameException('Game over.');
        }
    }

    /**
     * @throws InvalidGameException
     */
    protected function validateWord()
    {
        if (!$this->wordHandler->getWord()) {
            throw new InvalidGameException('Game not started.');
        }
    }

    /**
     * @param boolean $throwException
     *
     * @throws InvalidGameException
     *
     * @return boolean
     */
    public function validateGame($throwException = false)
    {
        try {
            $this->validateWord();
            $this->validateGuesses();
        } catch (InvalidGameException $exception) {
            if ($throwException) {
                throw $exception;
            }

            return false;
        }

        return true;
    }

    /**
     * @param string $letter
     *
     * @throws DuplicateGuessException
     */
    protected function validateGuess($letter)
    {
        if (in_array($letter, $this->invalidGuesses) || in_array($letter, $this->validGuesses)) {
            throw new DuplicateGuessException();
        }
    }

    /**
     * @param string $letter
     *
     * @throws ValidationException
     */
    protected function validateLetter($letter)
    {
        if (strlen($letter) > 1 || !ctype_alpha($letter)) {
            throw new ValidationException();
        }
    }
}
