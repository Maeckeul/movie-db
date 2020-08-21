<?php

namespace App\Repository;

use App\Entity\Movie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class MovieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Movie::class);
    }

    public function findWithActors($id) {

        $builder = $this->createQueryBuilder('movie');
        $builder->where("movie.id = :id");
        $builder->setParameter('id', $id);
        $builder->leftJoin('movie.movieActors', 'actor');
        $builder->addSelect('actor');
        $builder->leftJoin('actor.person', 'person');
        $builder->addSelect('person');

        $query = $builder->getQuery();
        $result = $query->getOneOrNullResult();

        return $result;
    }
}
