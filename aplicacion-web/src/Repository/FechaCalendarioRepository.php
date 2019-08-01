<?php

namespace App\Repository;

use App\Entity\FechaCalendario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method FechaCalendario|null find($id, $lockMode = null, $lockVersion = null)
 * @method FechaCalendario|null findOneBy(array $criteria, array $orderBy = null)
 * @method FechaCalendario[]    findAll()
 * @method FechaCalendario[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FechaCalendarioRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, FechaCalendario::class);
    }

//    /**
//     * @return FechaCalendario[] Returns an array of FechaCalendario objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FechaCalendario
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
