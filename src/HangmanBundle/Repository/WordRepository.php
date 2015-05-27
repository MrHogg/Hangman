<?php

namespace HangmanBundle\Repository;

use HangmanBundle\Interfaces\WordRepositoryInterface;

/**
 * Class WordRepository
 *
 * @package HangmanBundle\Repository
 */
class WordRepository implements WordRepositoryInterface
{
    /** @var array */
    protected $words = array(
        'antidisestablishmentarianism',
        'bikes',
        'cheeseburgers',
        'crackerjack',
        'fusion',
        'mammalian',
    );

    /**
     * @return string
     */
    public function getRandomWord()
    {
        return $this->words[array_rand($this->words)];
    }
}
