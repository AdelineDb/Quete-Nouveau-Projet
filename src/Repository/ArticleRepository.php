<?php

namespace App\Repository;

use App\Entity\Article;
use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function findAllWithCategoriesAndAuthors()
    {
        $qb = $this->createQueryBuilder('a')//table en cours
            ->innerJoin('a.category', 'c')//table en cours avec le nom de la méthode et l'alias pour la table de la méthode
            ->innerJoin('a.author', 'b')
            ->addSelect('c')
            ->addSelect('b')
            ->getQuery();


        return $qb->execute();
    }

    public function findAllWithCategoriesAndTagsAndAuthors()
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('SELECT a, c, b, t FROM App\Entity\Article a 
        INNER JOIN a.category c 
        INNER JOIN a.author b 
        LEFT JOIN a.tags t');

        return $query->execute();

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
