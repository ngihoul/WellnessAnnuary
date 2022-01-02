<?php

namespace App\Repository;

use App\Entity\Provider;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Provider|null find($id, $lockMode = null, $lockVersion = null)
 * @method Provider|null findOneBy(array $criteria, array $orderBy = null)
 * @method Provider[]    findAll()
 * @method Provider[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProviderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Provider::class);
    }

    public function findBySearch(string $what = null, string $whichCategory = null, string $where = null) {
        $queryBuilder = $this->createQueryBuilder('p')
            ->join('p.user', 'u')
            ->addSelect('u')
            ->join('u.locality', 'l')
            ->addSelect('l')
            ->join('l.postCode', 'pc')
            ->addSelect('pc')
            ->join('pc.municipality', 'm')
            ->addSelect('m')
            ->join('p.serviceCategories', 'c')
            ->addSelect('c');

        if($what) {
            $queryBuilder
                ->andWhere('p.name LIKE :what OR p.description LIKE :what')
                ->setparameter('what', '%'.$what.'%');
        }

        if($where) {
            $queryBuilder
                ->andWhere('l.name LIKE :where OR m.name LIKE :where OR pc.postCode LIKE :where')
                ->setparameter('where', '%'.$where.'%');
        }

        if($whichCategory) {
            $queryBuilder
                ->andWhere('c.name = :whichCategory')
                ->setparameter(':whichCategory', $whichCategory);
        }

        return $queryBuilder
            ->orderBy('p.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Provider[] Returns an array of Provider objects
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
    public function findOneBySomeField($value): ?Provider
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
