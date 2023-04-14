<?php

namespace App\Repository;

use App\Entity\Fruit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Fruit>
 *
 * @method Fruit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Fruit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Fruit[]    findAll()
 * @method Fruit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FruitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fruit::class);
    }

    public function save(Fruit $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Fruit $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function search(string $name = null, array $families = null, int $page = 1, int $limit = 10): array
    {
        $qb = $this->createQueryBuilder('f')
            ->orderBy('f.name', 'ASC');

        if ($name) {
            $qb->andWhere('f.name LIKE :name')
                ->setParameter('name', '%' . $name . '%');
        }

        if (!empty($families)) {
            $qb->andWhere('f.family IN (:families)')
                ->setParameter('families', $families);
        }

        $qb->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);

        return $qb->getQuery()->getResult();
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function countSearchResults(string $name = null, array $families = null): int
    {
        $qb = $this->createQueryBuilder('f')
            ->select('COUNT(f)');

        if ($name) {
            $qb->andWhere('f.name LIKE :name')
                ->setParameter('name', '%' . $name . '%');
        }

        if (!empty($families)) {
            $qb->andWhere('f.family IN (:families)')
                ->setParameter('families', $families);
        }

        return (int)$qb->getQuery()->getSingleScalarResult();
    }

    public function getFamilies(): array
    {
        return $this->createQueryBuilder('f')
            ->select('DISTINCT f.family')
            ->orderBy('f.family', 'ASC')
            ->getQuery()
            ->getSingleColumnResult();
    }

//    /**
//     * @return Fruit[] Returns an array of Fruit objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Fruit
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
