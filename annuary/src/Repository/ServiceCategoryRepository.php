<?php

namespace App\Repository;

use App\Entity\ServiceCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ServiceCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method ServiceCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method ServiceCategory[]    findAll()
 * @method ServiceCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServiceCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ServiceCategory::class);
    }

    public function getHighlighted() {
        return $this->createQueryBuilder('c')
            ->andWhere('c.highlighted = 1')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult()
        ;
    }

    public function getCategoriesNotChosenByProvider($id)
    {

        $sql = "SELECT name FROM service_category WHERE id NOT IN (SELECT service_category_id FROM provider_category WHERE provider_id = :id);";

        $catChosen = $this->createQueryBuilder('c1')
            ->leftJoin('c1.providers', 'p')
            ->where('p.id = :id');
        return $this->createQueryBuilder('c')
            ->where('c.id NOT IN (' . $catChosen->getDQL() . ')')
            ->setParameter(':id', $id)
            ->orderBy('c.name', 'ASC');
    }
}
