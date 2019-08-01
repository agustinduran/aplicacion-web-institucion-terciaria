<?php

namespace App\Repository;

use App\Entity\TeacherController;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TeacherController|null find($id, $lockMode = null, $lockVersion = null)
 * @method TeacherController|null findOneBy(array $criteria, array $orderBy = null)
 * @method TeacherController[]    findAll()
 * @method TeacherController[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeacherControllerRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TeacherController::class);
    }

//    /**
//     * @return TeacherController[] Returns an array of TeacherController objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TeacherController
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
