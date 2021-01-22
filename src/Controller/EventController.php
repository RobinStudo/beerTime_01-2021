<?php

namespace App\Controller;

use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/event", name="event_")
 */
class EventController extends AbstractController
{
    private $events;

    public function __construct()
    {
        $this->events = array(
            array(
                'id' => 1,
                'name' => 'Fête de la bière',
                'description' => 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit,',
                'startAt' => new DateTime('2021-02-12 14:00:00'),
                'endAt' => new DateTime('2021-02-14 22:00:00'),
                'picture' => 'https://resize-europe1.lanmedia.fr/r/622,311,forcex,center-middle/img/var/europe1/storage/images/europe1/international/en-images-la-fete-de-la-biere-de-munich-est-lancee-677880/13575068-1-fre-FR/EN-IMAGES-La-fete-de-la-biere-de-Munich-est-lancee.jpg',
                'price' => null,
                'capacity' => 1500,
                'place' => 'Munich',
            ),
            array(
                'id' => 99,
                'name' => 'Cours de brassage',
                'description' => 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit,',
                'startAt' => new DateTime('2021-01-24 14:00:00'),
                'endAt' => new DateTime('2021-01-24 18:00:00'),
                'picture' => 'https://www.potagerinterieur.info/wp-content/uploads/2019/03/Mon-Guide-dAchat-dun-kit-de-brassage-de-bi%C3%A8re.jpg',
                'price' => 80,
                'capacity' => 15,
                'place' => 'Brasserie Cambier',
            ),
            array(
                'id' => 120,
                'name' => 'Dégustation de Stout',
                'description' => 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit,',
                'startAt' => new DateTime('2021-01-14 09:00:00'),
                'endAt' => new DateTime('2021-01-30 18:00:00'),
                'picture' => 'https://rossi-distribution.fr/wp-content/uploads/2017/10/biere-1-1024x591.jpg',
                'price' => 10,
                'capacity' => 150,
                'place' => 'Grand Palais',
            ),
            array(
                'id' => 4,
                'name' => 'Nouvel an de la bière',
                'description' => 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit,',
                'startAt' => new DateTime('2020-12-31 14:00:00'),
                'endAt' => new DateTime('2021-01-01 11:00:00'),
                'picture' => 'https://cdn.radiofrance.fr/s3/cruiser-production/2020/08/c8f847a6-30f1-439c-91a0-09c264206580/1280x680_gettyimages-862672530.jpg',
                'price' => 120,
                'capacity' => 200,
                'place' => 'Paris',
            ),
        );
    }

    /**
     * @Route("", name="list")
     */
    public function list(): Response
    {
        return $this->render('event/list.html.twig', array(
            'events' => $this->events,
        ));
    }

    /**
     * @Route("/{id}", name="display", requirements={"id"="\d+"})
     */
    public function display($id): Response
    {
        foreach( $this->events as $item ){
            if( $item['id'] == $id ){
                $event = $item;
                break;
            }
        }

        if( empty( $event ) ){
            throw new NotFoundHttpException("L'événement n'existe pas");
        }

        return $this->render('event/display.html.twig', array(
            'event' => $event,
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
