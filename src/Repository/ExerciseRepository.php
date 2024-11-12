<?php

namespace App\Repository;

use App\Entity\Exercise;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Exercise>
 */
class ExerciseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Exercise::class);
    }

    /**
     * Create a new exercise.
     *
     * @param Exercise $exercise
     */
    public function create(Exercise $exercise)
    {
        $this->getEntityManager()->persist($exercise);
        $this->getEntityManager()->flush();
    }

    /**
     * Delete an exercise by ID.
     *
     * @param int $id
     */
    public function delete($id)
    {
        $existingExercise = $this->find($id);
        if (!is_null($existingExercise)) {
            $this->getEntityManager()->remove($existingExercise);
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Update an existing exercise.
     *
     * @param Exercise $exercise
     */
    public function update(Exercise $exercise)
    {
        $this->create($exercise);
    }

    /**
     * Find an exercise by its name.
     *
     * @param string $name
     * @return Exercise|null
     */
    public function findByName(string $name): ?Exercise
    {
        return $this->findOneBy(['name' => $name]);
    }

    /**
     * Find exercises by muscle group.
     *
     * @param string $muscleGroup
     * @return Exercise[]
     */
    public function findByMuscleGroup(string $muscleGroup): array
    {
        return $this->findBy(['muscleGroup' => $muscleGroup]);
    }

    /**
     * Find an exercise by name excluding a specific ID.
     *
     * @param string $name
     * @param int $id
     * @return Exercise|null
     */
    public function findByNameExcludingId(string $name, int $id): ?Exercise
    {
        return $this->createQueryBuilder('e')
            ->where('e.name = :name')
            ->andWhere('e.id != :id')
            ->setParameter('name', $name)
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    // Uncomment and implement these methods for additional functionality
    /*
    /**
     * @return Exercise[] Returns an array of Exercise objects
     */
    public function findByExampleField($value): array
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    public function findOneBySomeField($value): ?Exercise
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Find exercises associated with a workout by its ID.
     *
     * @param int $id
     * @return Exercise[]
     */
    public function findByWorkout($id)
    {
        return $this->findBy(['id' => $id]);
    }
}
