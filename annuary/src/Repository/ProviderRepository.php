<?php

namespace App\Repository;

use App\Entity\Provider;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

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

    /**
     * Query for search feature - By name OR/AND By Category OR/AND By localization
     * @param $what
     * @param $whichCategory
     * @param $where
     * @return mixed
     */
    public function findBySearch($what = null, $whichCategory = 0, $where = null) {
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
                ->andWhere('c.id = :whichCategory')
                ->setparameter(':whichCategory', $whichCategory);
        }

        return $queryBuilder
            ->orderBy('p.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findForAutoCompletion($query) {
        return $this->createQueryBuilder('p')
            ->select('p.id', 'p.name', 'p.description')
            ->andWhere('p.name LIKE :value OR p.description LIKE :value')
            ->setParameter(':value', '%'.$query.'%')
            ->orderBy('p.name', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    public function getLastSubscribers($start, $offset): Paginator
    {
        $query = $this->createQueryForLastSubscribers()
            ->setFirstResult($start)
            ->setMaxResults($offset)
            ->getQuery();

        return $paginator = new Paginator($query, true);
    }

    private function createQueryForLastSubscribers() {
        return $this->createQueryBuilder('p')
            ->join('p.user', 'u')
            ->addSelect('u')
            ->join('u.locality', 'l')
            ->addSelect('l')
            ->join('l.postCode', 'pc')
            ->addSelect('pc')
            ->join('pc.municipality', 'm')
            ->addSelect('m')
            ->join('p.serviceCategories', 'c')
            ->addSelect('c')
            ->orderBy('u.registeredOn', 'DESC')
            ->addOrderBy('p.name', 'ASC');
    }
}
