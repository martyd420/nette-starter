<?php

declare(strict_types=1);

namespace App\Model\Core\Repository;

use App\Model\Core\Entity\Log;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class LogRepository
{
    /** @var EntityRepository<Log> */
    private EntityRepository $repository;

    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
        $this->repository = $this->entityManager->getRepository(Log::class);
    }

    public function save(Log $log): void
    {
        $this->entityManager->persist($log);
        $this->entityManager->flush();
    }

    public function getEntityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }

    public function createQueryBuilder(string $alias = 'l'): \Doctrine\ORM\QueryBuilder
    {
        return $this->repository->createQueryBuilder($alias);
    }

    public function deleteOlderThan(int $days): int
    {
        $date = new \DateTimeImmutable("- $days days");

        $count = $this->createQueryBuilder('l')
            ->delete()
            ->where('l.createdAt < :date')
            ->setParameter('date', $date)
            ->getQuery()
            ->execute();

        return (int) $count;
    }
}
