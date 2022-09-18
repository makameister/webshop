<?php

namespace App\Repository;

use App\Entity\Search\ProductSearch;
use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function findAllQuery()
    {
        return $this->createQueryBuilder('p')
            ->getQuery()
            ->getResult();
    }

    public function findMostRecent()
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.created_at', 'DESC')
            ->setMaxResults(6)
            ->getQuery()
            ->getResult();
    }

    public function findByCategoryQuery(?string $category)
    {
        return $this->createQueryBuilder('p')
            ->innerJoin(Category::class, 'c', 'WITH', 'p.category = c.id')
            ->where('c.name = :category_name')
            ->setParameter('category_name', $category);
    }

    public function findByCategoryAll(?string $category)
    {
        return $this->findByCategoryQuery($category)
            ->getQuery()
            ->getResult();
    }

    public function findFilteredByCategoryQuery(string $category, ProductSearch $search): Query
    {
        $query = $this->findByCategoryQuery($category);

        if ($search->getMaxPrice() !== null) {
            $query->andWhere('p.price <= :max_price')
                ->setParameter('max_price', $search->getMaxPrice());
        }

        if ($search->getBrand()) {
            $query->andWhere('p.brand = :brand')
                ->setParameter('brand', $search->getBrand()->getBrand());
        }

        return $query->getQuery();
    }

    public function findByCategoryWithFilters(string $category, ProductSearch $search)
    {
        return $this->findFilteredByCategoryQuery($category, $search)
            ->getResult();
    }

}