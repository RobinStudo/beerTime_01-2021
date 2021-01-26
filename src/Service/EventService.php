<?php
namespace App\Service;

use App\Repository\EventRepository;
use App\Repository\ParticipationRepository;

class EventService{
    private $repository;
    private $participationRepository;

    public function __construct( EventRepository $repository, ParticipationRepository $participationRepository )
    {
        $this->repository = $repository;
        $this->participationRepository = $participationRepository;
    }

    public function buildResult($query)
    {
        return $this->repository->search( $query );
    }

    public function getOne($id)
    {
        return $this->repository->find($id);
    }

    public function countParticipant( $event )
    {
        return $this->participationRepository->count(array(
            'event' => $event,
        ));
    }
}
