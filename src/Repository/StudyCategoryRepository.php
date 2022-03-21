<?php

namespace App\Repository;

use App\Entity\StudyCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StudyCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method StudyCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method StudyCategory[]    findAll()
 * @method StudyCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StudyCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StudyCategory::class);
    }

    public function countCategories()
    {
        return $this->createQueryBuilder('c')
            ->select('count(c.id) total')
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    // /**
    //  * @return StudyCategory[] Returns an array of StudyCategory objects
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
    public function findOneBySomeField($value): ?StudyCategory
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
