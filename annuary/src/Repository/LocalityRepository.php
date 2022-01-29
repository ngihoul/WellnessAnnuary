<?php

namespace App\Repository;

use App\Entity\Locality;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Locality|null find($id, $lockMode = null, $lockVersion = null)
 * @method Locality|null findOneBy(array $criteria, array $orderBy = null)
 * @method Locality[]    findAll()
 * @method Locality[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LocalityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Locality::class);
    }

    public function findForAutoCompletion($query) {
        return $this->createQueryBuilder('l')
            ->select('l.id AS id, l.name AS locality')
            ->join('l.postCode', 'p')
            ->addSelect('p.postCode AS postCode')
            ->join('p.municipality', 'm')
            ->addSelect('m.name AS municipality')
            ->andWhere('m.name LIKE :value')
            ->orWhere('p.postCode LIKE :value')
            ->orWhere('l.name LIKE :value')
            ->setParameter(':value', '%' . $query . '%')
            ->addOrderBy('l.name', 'ASC')
            ->addOrderBy('m.name', 'ASC')
            ->addOrderBy('p.postCode', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }
}
