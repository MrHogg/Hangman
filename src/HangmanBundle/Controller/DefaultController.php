<?php

namespace HangmanBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DefaultController
 *
 * @package HangmanBundle\Controller
 */
class DefaultController extends Controller
{
    /**
     * @Route(path="/", methods={"GET"})
     *
     * @return RedirectResponse
     */
    public function indexAction()
    {
        return $this->redirectToRoute('hangman_hangman_index');
    }
}
