<?php

namespace App\Repository;

use App\Entity\TodoListItemFinished;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TodoListItemFinished>
 *
 * @method TodoListItemFinished|null find($id, $lockMode = null, $lockVersion = null)
 * @method TodoListItemFinished|null findOneBy(array $criteria, array $orderBy = null)
 * @method TodoListItemFinished[]    findAll()
 * @method TodoListItemFinished[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TodoListItemFinishedRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TodoListItemFinished::class);
    }

    public function save(TodoListItemFinished $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(TodoListItemFinished $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return TodoListItemFinished[] Returns an array of TodoListItemFinished objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TodoListItemFinished
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
