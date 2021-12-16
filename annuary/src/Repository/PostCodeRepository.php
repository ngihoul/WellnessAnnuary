<?php

namespace App\Repository;

use App\Entity\PostCode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PostCode|null find($id, $lockMode = null, $lockVersion = null)
 * @method PostCode|null findOneBy(array $criteria, array $orderBy = null)
 * @method PostCode[]    findAll()
 * @method PostCode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostCodeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PostCode::class);
    }

    // /**
    //  * @return PostCode[] Returns an array of PostCode objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PostCode
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
