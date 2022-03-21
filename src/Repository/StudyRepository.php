<?php

namespace App\Repository;

use App\Entity\Client;
use App\Entity\Project;
use App\Entity\Study;
use App\Entity\StudyCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Study|null find($id, $lockMode = null, $lockVersion = null)
 * @method Study|null findOneBy(array $criteria, array $orderBy = null)
 * @method Study[]    findAll()
 * @method Study[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StudyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Study::class);
    }

    public function countStudies()
    {
        return $this->createQueryBuilder('s')
            ->select('count(s.id) total')
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    public function lastStudies($limit)
    {
        return $this->createQueryBuilder('s')
            ->orderBy('s.id', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
            ;
    }

    public function recentStudies($date)
    {
        return $this->createQueryBuilder('s')
            ->where('s.startDate >= :date')
            ->setParameter(':date', new \DateTime($date))
            ->orderBy('s.category', 'ASC')
            ->addOrderBy('s.startDate', 'DESC')
            ->addOrderBy('s.title', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findAllOrderByClient($date = null)
    {
        $builder = $this->createQueryBuilder('s')
            ->where('s.hideClient IS NULL')
            ->orWhere('s.hideClient = :val')
            ->setParameter('val', 0)
            ->join(Client::class, 'c', Join::WITH, 's.client = c.id')
            ->andWhere('c.hide IS NULL')
            ->orWhere('c.hide = :valc')
            ->setParameter('valc', 0)
            ->orderBy('c.name', 'ASC')
            ->addOrderBy('s.startDate', 'DESC')
            ->addOrderBy('s.title', 'ASC')
            ;
        if ($date && "null" !== $date) {
           $builder->where('s.startDate >= :date')
                ->setParameter(':date', new \DateTime($date));
        }
        return $builder->getQuery()->getResult();
    }

    public function findAllOrderByProject($date = null)
    {
        $builder = $this->createQueryBuilder('s')
            ->join(Project::class, 'p', Join::WITH, 's.project = p.id')
            ->orderBy('p.name', 'ASC')
            ->addOrderBy('s.startDate', 'DESC')
            ->addOrderBy('s.title', 'ASC')
        ;
        if ($date && "null" !== $date) {
            $builder->where('s.startDate >= :date')
                ->setParameter(':date', new \DateTime($date));
        }
        return $builder->getQuery()->getResult();
    }

    public function findAllGroupByCat()
    {
        return $this->createQueryBuilder('s')
            ->select('count(s.id) as total')
            ->join(StudyCategory::class, 'c', Join::WITH, 's.category = c.id')
            ->addSelect('c.name')
            ->groupBy('c.name')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findAllGroupByProject()
    {
        return $this->createQueryBuilder('s')
            ->select('count(s.id) as total')
            ->join(Project::class, 'p', Join::WITH, 's.project = p.id')
            ->addSelect('p.name')
            ->groupBy('p.name')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findAllGroupByClient()
    {
        return $this->createQueryBuilder('s')
            ->select('count(s.id) as total')
            ->join(Client::class, 'c', Join::WITH, 's.client = c.id')
            ->addSelect('c.name')
            ->groupBy('c.name')
            ->getQuery()
            ->getResult()
            ;
    }



    // /**
    //  * @return Study[] Returns an array of Study objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Study
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
