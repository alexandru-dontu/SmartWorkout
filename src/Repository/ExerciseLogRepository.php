<?php

namespace App\Repository;

use App\Entity\ExerciseLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ExerciseLog>
 */
class ExerciseLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExerciseLog::class);
    }

    /**
     * Find exercise logs by exercise ID and user ID.
     *
     * @param int $exerciseId
     * @param int $userId
     * @return ExerciseLog[]
     */
    public function findLogsByExerciseAndUser(int $exerciseId, int $userId): array
    {
        return $this->createQueryBuilder('el')
            ->join('el.workout', 'w') // Join the Workout entity associated with the ExerciseLog
            ->addSelect('w') // Include the Workout entity in the results
            ->where('el.exercise = :exerciseId') // Filter by exercise ID
            ->andWhere('w.person = :userId') // Filter by user ID
            ->setParameter('exerciseId', $exerciseId) // Set the exercise ID parameter
            ->setParameter('userId', $userId) // Set the user ID parameter
            ->getQuery() // Create and return the query
            ->getResult(); // Execute the query and return the result
    }

    // Uncomment and implement these methods for additional functionality
    /*
    /**
     * @return ExerciseLog[] Returns an array of ExerciseLog objects
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

    public function findOneBySomeField($value): ?ExerciseLog
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
