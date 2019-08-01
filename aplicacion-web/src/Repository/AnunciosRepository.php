<?php

namespace App\Repository;

use App\Entity\Anuncios;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Anuncios|null find($id, $lockMode = null, $lockVersion = null)
 * @method Anuncios|null findOneBy(array $criteria, array $orderBy = null)
 * @method Anuncios[]    findAll()
 * @method Anuncios[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnunciosRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Anuncios::class);
    }

//    /**
//     * @return Anuncios[] Returns an array of Anuncios objects
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
    public function findOneBySomeField($value): ?Anuncios
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
