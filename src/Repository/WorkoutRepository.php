<?php

namespace App\Repository;

use App\Entity\Workout;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Workout>
 */
class WorkoutRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Workout::class);
    }

    /**
     * Create a new workout and save it to the database.
     *
     * @param Workout $workout
     */
    public function create(Workout $workout)
    {
        $this->getEntityManager()->persist($workout);
        $this->getEntityManager()->flush();
    }

    /**
     * Find a workout by its name.
     *
     * @param string $name
     * @return Workout|null
     */
    public function findByName(string $name): ?Workout
    {
        return $this->findOneBy(['name' => $name]);
    }

    // Uncomment and implement these methods for additional functionality
    /*
    /**
     * @return Workout[] Returns an array of Workout objects
     */
    public function findByExampleField($value): array
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    public function findOneBySomeField($value): ?Workout
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Delete a workout by its ID.
     *
     * @param int $id
     */
    public function delete(int $id)
    {
        $existingWorkout = $this->find($id);
        if (!is_null($existingWorkout)) {
            $this->getEntityManager()->remove($existingWorkout);
            $this->getEntityManager()->flush();
        }
    }
}
