<?php

namespace App\Repository;

use App\Entity\AusenciaDocente;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method AusenciaDocente|null find($id, $lockMode = null, $lockVersion = null)
 * @method AusenciaDocente|null findOneBy(array $criteria, array $orderBy = null)
 * @method AusenciaDocente[]    findAll()
 * @method AusenciaDocente[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AusenciaDocenteRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AusenciaDocente::class);
    }

//    /**
//     * @return AusenciaDocente[] Returns an array of AusenciaDocente objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AusenciaDocente
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
