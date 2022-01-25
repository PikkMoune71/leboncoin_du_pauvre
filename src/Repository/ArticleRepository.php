<?php

namespace App\Repository;

use App\Classe\Search;
use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    /**
     * Requête qui permet de récupérer les produits en fonction de la recherche de l'utilisateur
     * @return Article[]
     */
    public function findWithSearch(Search $search)
    {
        $query = $this
            ->createQueryBuilder('a')
            ->select('t', 'a')
            ->join('a.tags', 't');

        if(!empty($search->tags)){
            $query = $query
                ->andWhere('t.id IN (:tags)')
                ->setParameter('tags', $search->tags);
        }

        if(!empty($search->string)){
            $query = $query
                ->andWhere('a.name LIKE :string')
                ->setParameter('string', "%{$search->string}%");
        }
        return $query->getQuery()->getResult();
    }
    // /**
    //  * @return Article[] Returns an array of Article objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Article
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
