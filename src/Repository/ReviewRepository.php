<?php

namespace App\Repository;

use App\Entity\Review;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Review>
 */
class ReviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Review::class);
    }

    /**
     * @return Paginator<Review>
     */
    public function paginate(int $page, int $limit = 10): Paginator
    {
        $query = $this->createQueryBuilder('r')
            ->orderBy('r.createdAt', 'DESC')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery();

        return new Paginator($query);
    }

    /**
     * @return list<array{companyName: string, reviewCount: int, averageRating: string}>.
     */
    public function getCompanyStats(): array
    {
        return $this->createQueryBuilder('r')
            ->select('r.companyName AS companyName')
            ->addSelect('COUNT(r.id) AS reviewCount')
            ->addSelect('AVG(r.rating) AS averageRating')
            ->groupBy('r.companyName')
            ->orderBy('averageRating', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
