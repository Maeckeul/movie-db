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

    public function findByPartialTitle($partialTitle) {

        $builder = $this->createQueryBuilder('movie');
        $builder->where("movie.title LIKE :partialTitle");
        $builder->setParameter('partialTitle', "%$partialTitle%");
        $builder->orderBy('movie.title', 'ASC');
        $query = $builder->getQuery();
        return $query->execute();
    }

    public function findWithFullData($id) {

        $builder = $this->createQueryBuilder('movie');
        $builder->where("movie.id = :id");
        $builder->setParameter('id', $id);

        $builder->leftJoin('movie.movieActors', 'actor');
        $builder->addSelect('actor');

        $builder->leftJoin('actor.person', 'person');
        $builder->addSelect('person');

        $builder->leftJoin('movie.posts', 'post');
        $builder->addSelect('post');

        $builder->leftJoin('movie.director', 'director');
        $builder->addSelect('director');

        $builder->leftJoin('movie.writers', 'writer');
        $builder->addSelect('writer');

        $builder->leftJoin('movie.categories', 'category');
        $builder->addSelect('category');

        $builder->leftJoin('movie.awards', 'award');
        $builder->addSelect('award');


        $query = $builder->getQuery();

        $result = $query->getOneOrNullResult();

        return $result;
    }
}
