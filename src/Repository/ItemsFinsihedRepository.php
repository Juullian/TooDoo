<?php

namespace App\Repository;

use App\Entity\ItemsFinsihed;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ItemsFinsihed>
 *
 * @method ItemsFinsihed|null find($id, $lockMode = null, $lockVersion = null)
 * @method ItemsFinsihed|null findOneBy(array $criteria, array $orderBy = null)
 * @method ItemsFinsihed[]    findAll()
 * @method ItemsFinsihed[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemsFinsihedRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ItemsFinsihed::class);
    }

    public function save(ItemsFinsihed $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ItemsFinsihed $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return ItemsFinsihed[] Returns an array of ItemsFinsihed objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ItemsFinsihed
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
