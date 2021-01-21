<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/event", name="event_")
 */
class EventController extends AbstractController
{
    /**
     * @Route("", name="list")
     */
    public function list(): Response
    {
        return $this->render('event/list.html.twig');
    }

    /**
     * @Route("/{id}", name="display", requirements={"id"="\d+"})
     */
    public function display($id): Response
    {
        return $this->render('event/display.html.twig');
    }

    /**
     * @Route("/create", name="create")
     */
    public function create(): Response
    {
        return $this->render('event/create.html.twig');
    }

    /**
     * @Route("/{id}/join", name="join", requirements={"id"="\d+"})
     */
    public function join($id): Response
    {
        return $this->render('event/join.html.twig');
    }
}
