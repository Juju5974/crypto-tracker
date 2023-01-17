<?php

namespace App\Repository;

use App\Entity\Valuation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Valuation>
 *
 * @method Valuation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Valuation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Valuation[]    findAll()
 * @method Valuation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ValuationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Valuation::class);
    }

    public function add(Valuation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Valuation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findLastDate(): array
    {
        return $this->createQueryBuilder('v')
            ->orderBy('v.date', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();
    }

    public function findSevenLastDate(): array
    {
        return $this->createQueryBuilder('v')
            ->orderBy('v.date', 'DESC')
            ->setMaxResults(7)
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return Valuation[] Returns an array of Valuation objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('v.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Valuation
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
