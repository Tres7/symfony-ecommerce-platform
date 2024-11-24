<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

//    /**
//     * @return Product[] Returns an array of Product objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Product
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

// src/Repository/ProductRepository.php
    public function searchByName(string $query): array
    {
        return $this->createQueryBuilder('p')
            ->where('p.name LIKE :query')
            ->setParameter('query', '%' . $query . '%')
            ->setMaxResults(10) // Limiter les résultats pour de meilleures performances
            ->getQuery()
            ->getResult();
    }


    /**
     * get all products with their category
     * @return array
     */
    public function countProductsByCategory(): array
    {
        return $this->createQueryBuilder('p')
            ->select('c.name as category, count(p.id) as count')
            ->join('p.category', 'c')
            ->groupBy('c.id')
            ->getQuery()
            ->getResult();
    }

    public function countProductByStatus(): array
    {
        return $this->createQueryBuilder('p')
            ->select('p.status as status, count(p.id) as count')
            ->groupBy('p.status')
            ->getQuery()
            ->getResult();
    }

}
