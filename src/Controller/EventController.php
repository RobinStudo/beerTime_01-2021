<?php
namespace App\Controller;

use App\Entity\Event;
use App\Entity\Participation;
use App\Form\EventType;
use App\Repository\ParticipationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Service\EventService;

/**
 * @Route("/event", name="event_")
 */
class EventController extends AbstractController
{
    private $em;
    private $eventService;

    public function __construct(EntityManagerInterface $em,EventService $eventService)
    {
        $this->em = $em;
        $this->eventService = $eventService;
    }

    /**
     * @Route("", name="list")
     */
    public function list( Request $request ): Response
    {
        $query = $request->query->get('q');
        $sort = $request->query->get('sort');

        $events = $this->eventService->buildResult( $query, $sort );
        return $this->render('event/list.html.twig', array(
            'events' => $events,
            'query' => $query,
        ));
    }

    /**
     * @Route("/{id}", name="display", requirements={"id"="\d+"})
     */
    public function display(ParticipationRepository $participationRepository, $id): Response
    {
        $event = $this->eventService->getOne($id);

        if (empty($event)) {
            throw new NotFoundHttpException("L'événement n'existe pas");
        }

        if( $this->getUser() ){
            $hasParticipation = $participationRepository->count(array(
                'event' => $event,
                'user' => $this->getUser(),
            ));
        }else{
            $hasParticipation = 0;
        }


        return $this->render('event/display.html.twig', array(
            'event' => $event,
            'participantCounter' => $this->eventService->countParticipant( $event ),
            'hasParticipation' => $hasParticipation,
        ));
    }

    /**
     * @Route("/create", name="create")
     * @IsGranted("ROLE_USER")
     */
    public function create(Request $request, UserRepository $userRepository): Response
    {
        $event = new Event();
        $form = $this->createForm( EventType::class, $event );

        $form->handleRequest($request);
        if( $form->isSubmitted() && $form->isValid() ){
            $event->setOwner($this->getUser());

            $this->em->persist($event);
            $this->em->flush();

            $this->addFlash('success', 'Votre événement est bien créé');
            return $this->redirectToRoute('event_display', array(
                'id' => $event->getId(),
            ));
        }

        return $this->render('event/create.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/{id}/join", name="join", requirements={"id"="\d+"}, methods={"POST"})
     * @IsGranted("ROLE_USER")
     */
    public function join($id): Response
    {
        $event = $this->eventService->getOne($id);
        $user = $this->getUser();
        $bookingNumber = bin2hex(random_bytes(12));

        $participation = new Participation();
        $participation->setEvent($event);
        $participation->setUser($user);
        $participation->setBookingNumber($bookingNumber);

        $this->em->persist($participation);
        $this->em->flush();

        return new JsonResponse(array(
            'status' => true
        ));
    }
}
