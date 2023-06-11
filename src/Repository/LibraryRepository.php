<?php

namespace App\Repository;

use App\Entity\Library;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Library>
 *
 * @method Library|null find($id, $lockMode = null, $lockVersion = null)
 * @method Library|null findOneBy(array $criteria, array $orderBy = null)
 * @method Library[]    findAll()
 * @method Library[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LibraryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Library::class);
    }

    public function save(Library $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Library $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    public function findByName($name, $limit, $offset)
//    {
//        return $this->createQueryBuilder('l')
//            ->where('l.name LIKE :name')
//            ->setParameter('name', '%'.$name.'%')
//            ->setMaxResults($limit)
//            ->setFirstResult($offset)
//            ->getQuery()
//            ->getResult();
//    }

    public function findByTown($town, $limit, $offset)
    {
        $subQuery = $this->createQueryBuilder('l2')
            ->select('MIN(l2.id)')
            ->where('l2.Town LIKE :Town')
            ->groupBy('l2.name')
            ->getQuery()
            ->getDQL();

        return $this->createQueryBuilder('l')
            ->where('l.Town LIKE :Town')
            ->andWhere('l.id IN (' . $subQuery . ')')
            ->setParameter('Town', '%'.$town.'%')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult();
    }

    public function findDistinctNames($size, $offset)
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT l
                FROM App:Library l
                WHERE l.id IN (
                    SELECT MIN(l2.id)
                    FROM App:Library l2
                    GROUP BY l2.name
                )
                ORDER BY l.id ASC'
            )
            ->setMaxResults($size)
            ->setFirstResult($offset)
            ->getResult();
    }


//    /**
//     * @return Library[] Returns an array of Library objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('l.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Library
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
