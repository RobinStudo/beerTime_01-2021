<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\EventService;

/**
 * @Route("/event", name="event_")
 */
class EventController extends AbstractController
{
    private $eventService;

    public function __construct(EventService $eventService)
    {
        $this->eventService = $eventService;
    }

    /**
     * @Route("", name="list")
     */
    public function list( Request $request ): Response
    {
        $query = $request->query->get('q');

        $events = $this->eventService->buildResult( $query );
        return $this->render('event/list.html.twig', array(
            'events' => $events,
            'query' => $query,
        ));
    }

    /**
     * @Route("/{id}", name="display", requirements={"id"="\d+"})
     */
    public function display($id): Response
    {
        $event = $this->eventService->getOne($id);

        if (empty($event)) {
            throw new NotFoundHttpException("L'événement n'existe pas");
        }

        return $this->render('event/display.html.twig', array(
            'event' => $event,
            'participantCounter' => $this->eventService->countParticipant( $event ),
        ));
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
