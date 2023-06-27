<?php

namespace App\Repository;

use App\Entity\Image;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\Persistence\ManagerRegistry;

class ImageRepository extends ServiceEntityRepository
{

    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Image::class);
    }

    /**
     * @param Image $img
     * @param string $msg
     * @return void
     */
    public function addError(Image $img, string $msg): void
    {
        $em = $this->getEntityManager();
        $img->setError($msg);
        $em->flush();
    }

    /**
     * @param Image $orig, should be managed entity
     * @param Image $new
     * @return void
     * @throws ORMException
     */
    public function replace(Image $orig, Image $new): Image
    {
        $em = $this->getEntityManager();
        if (! $em->contains($orig) && ($id = $orig->getId()) === null) {
            throw new ORMException('$orig should be managed.');
        }
        $new->setId($orig->getId());
        $em->detach($orig);
        return $em->merge($new);
    }

    /**
     * @param string $src
     * @return Image|null
     */
    public function findBySrc(string $src): ?Image
    {
        return count($res = $this->findBy(['src'=>$src])) > 0? $res[0] : null;
    }

    /**
     * @param Image $image
     * @return void
     */
    public function clearCache(Image $image): void
    {
        $em = $this->getEntityManager();
        foreach($image->getCaches() as $cached)
        {
            $em->remove($cached);
        }
        $em->flush();
    }

    /**
     * @param Image $image
     * @return void
     */
    public function store(Image $image): void
    {
        $em = $this->getEntityManager();
        $em->persist($image);
        $em->flush();
    }
}