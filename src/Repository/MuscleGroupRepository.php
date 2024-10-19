<?php

namespace App\Repository;

use App\Entity\MuscleGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MuscleGroup>
 */
class MuscleGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MuscleGroup::class);
    }

    /**
     * Create a new muscle group.
     *
     * @param MuscleGroup $muscleGroup
     */
    public function create(MuscleGroup $muscleGroup)
    {
        $this->getEntityManager()->persist($muscleGroup);
        $this->getEntityManager()->flush();
    }

    /**
     * Find a muscle group by its name.
     *
     * @param string $name
     * @return MuscleGroup|null
     */
    public function findByName(string $name): ?MuscleGroup
    {
        return $this->findOneBy(['name' => $name]);
    }

    // Uncomment and implement these methods for additional functionality
    /*
    /**
     * @return MuscleGroup[] Returns an array of MuscleGroup objects
     */
    public function findByExampleField($value): array
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    public function findOneBySomeField($value): ?MuscleGroup
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
