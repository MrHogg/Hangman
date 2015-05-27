<?php

namespace HangmanBundle\Controller;

use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HangmanController
 *
 * @package HangmanBundle\Controller
 */
class HangmanController extends Controller
{
    /**
     * @Route(path="/hangman", methods={"GET"})
     *
     * @Template
     *
     * @return array|RedirectResponse
     */
    public function indexAction()
    {
        if ($this->get('hangman.word_helper')->validateGame()) {
            return $this->redirectToRoute('hangman_hangman_play');
        }

        return array();
    }

    /**
     * @Route(path="/hangman/play", methods={"GET"})
     *
     * @Template
     *
     * @return array
     */
    public function playAction()
    {
        $wordHelper = $this->get('hangman.word_helper');

        if (!$wordHelper->validateGame() && !$wordHelper->isGameLost()) {
            $wordHelper->generateWord();
        }

        return $wordHelper->getData();
    }

    /**
     * @Route(path="/hangman/play/{letter}", methods={"GET", "POST"})
     *
     * @param Request $request
     * @param string  $letter
     *
     * @throws Exception
     *
     * @return Response
     */
    public function guessAction(Request $request, $letter)
    {
        $wordHelper = $this->get('hangman.word_helper');
        $isAjax = $request->isXmlHttpRequest();

        try {
            $wordHelper->guess($letter);

            $output = $wordHelper->getData();
        } catch (Exception $exception) {
            if (!$isAjax) {
                $this->get('session')->getFlashBag()->add('error', $exception->getMessage());
            }

            $output = array('error' => $exception->getMessage());
        }

        if ($isAjax) {
            return new JsonResponse($output);
        }

        return $this->redirectToRoute('hangman_hangman_play');
    }

    /**
     * @Route(path="/hangman/reset", methods={"GET"})
     *
     * @return RedirectResponse
     */
    public function resetAction()
    {
        $this->get('hangman.word_handler')->resetGame();

        return $this->redirectToRoute('hangman_hangman_play');
    }
}
