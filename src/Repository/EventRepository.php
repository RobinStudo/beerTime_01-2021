<?php
namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    public function search( $query )
    {
        $stmt = $this->createQueryBuilder('e');

        if( !empty( $query ) ){
            $stmt->where('e.name LIKE :query');
            $stmt->setParameter('query', '%' . $query . '%');
        }

        return $stmt->getQuery()->getResult();
    }
}
