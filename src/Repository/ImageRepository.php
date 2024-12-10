<?php

namespace App\Repository;

use App\Entity\Image;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ImageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Image::class);
    }

    public function save(Image $image): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($image);
        $entityManager->flush();
    }

    public function delete(Image $image): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->remove($image);
        $entityManager->flush();
    }

    public function findImageByPath(string $path): ?Image
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.path = :path')
            ->setParameter('path', $path)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
