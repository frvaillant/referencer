<?php

namespace App\Repository;

use App\Entity\City;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method City|null find($id, $lockMode = null, $lockVersion = null)
 * @method City|null findOneBy(array $criteria, array $orderBy = null)
 * @method City[]    findAll()
 * @method City[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, City::class);
    }

    public function findWhereNameLike($cityName)
    {
        $cityName = strtoupper($cityName);
        $results = $this->createQueryBuilder('c')
            ->where('c.cityName LIKE :val')
            ->setParameter('val', '%' . $cityName . '%')
            ->orWhere('c.cityName LIKE :va')
            ->setParameter('va', '%' . $cityName)
            ->orWhere('c.cityName LIKE :v')
            ->setParameter('v',
                $cityName . '%')
            ->orderBy('c.cityName', 'ASC')
            ->getQuery()
            ->getResult()
            ;
        return $results;
    }

    public function getRandomCity()
    {
        $id_limits = $this->createQueryBuilder('c')
            ->select('MIN(c.id)', 'MAX(c.id)')
            ->getQuery()
            ->getOneOrNullResult();
        $random_possible_id = rand($id_limits[1], $id_limits[2]);

        return $this->createQueryBuilder('c')
            ->where('c.id >= :random_id')
            ->setParameter('random_id', $random_possible_id)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    // /**
    //  * @return City[] Returns an array of City objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?City
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
