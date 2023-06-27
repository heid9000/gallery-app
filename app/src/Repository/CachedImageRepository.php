<?php

namespace App\Repository;

use App\Entity\Cached;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CachedImageRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cached::class);
    }

    /**
     * @param string $name
     * @param string $size
     * @return Cached|null
     */
    public function findBySize(string $name, string $size): ?Cached
    {
        $cb = $this->createQueryBuilder('ci');
        $query = $cb
            ->join('ci.image', 'i')
            ->join('ci.dimension', 'd')
            ->andWhere('i.name = :img_name')
            ->andWhere('d.name = :dim_name')
            ->setParameter('img_name', $name)
            ->setParameter('dim_name', $size)
            ->getQuery();
        $res = $query->getResult();
        return count($res) > 0? $res[0] : null;
    }

    /**
     * @param Cached $cache
     * @return void
     */
    public function store(Cached $cache): void
    {
        $this->getEntityManager()->persist($cache);
        $this->getEntityManager()->flush();
    }
}