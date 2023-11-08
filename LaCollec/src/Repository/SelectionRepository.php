<?php

namespace App\Repository;

use App\Entity\Selection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Selection>
 *
 * @method Selection|null find($id, $lockMode = null, $lockVersion = null)
 * @method Selection|null findOneBy(array $criteria, array $orderBy = null)
 * @method Selection[]    findAll()
 * @method Selection[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SelectionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Selection::class);
    }

//    /**
//     * @return Selection[] Returns an array of Selection objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Selection
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    /**
     * Find entities where the 'Description' field starts with a specific string.
     *
     * @param string $prefix The prefix to search for.
     * @return Selection[]
     */
    public function findByDescription($prefix)
    {
        return $this->createQueryBuilder('e')
        ->join('e.member', 'm')
        ->join('e.video', 'v')
        ->Where('e.description LIKE :prefix')
        ->orWhere('m.nom LIKE :prefix')
        ->orWhere('v.nom LIKE :prefix')
        ->setParameter('prefix', $prefix . '%')
        ->getQuery()
        ->getResult();
    }
}