<?php

declare(strict_types=1);

namespace App\Model\Core\Repository;

use App\Model\Core\Entity\Log;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class LogRepository
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {}

    public function save(Log $log): void
    {
        $this->entityManager->persist($log);
        $this->entityManager->flush();
    }
}
