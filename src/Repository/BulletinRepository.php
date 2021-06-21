<?php

namespace App\Repository;

use App\Entity\Bulletin;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Bulletin|null find($id, $lockMode = null, $lockVersion = null) //* une seule entrée sera rendu
 * @method Bulletin|null findOneBy(array $criteria, array $orderBy = null) //* une seule entrée sera rendu
 * @method Bulletin[]    findAll() //* un tableau d'entrée sera rendu
 * @method Bulletin[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null) //* un tableau d'entrée sera rendu
 */
class BulletinRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bulletin::class);
    }

    // /**
    //  * @return Bulletin[] Returns an array of Bulletin objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Bulletin
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
