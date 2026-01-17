<?php

declare(strict_types=1);

namespace App\Model\User\Repository;

use App\Model\User\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class UserRepository
{
	/** @var EntityRepository<User> */
	private EntityRepository $repository;

	public function __construct(
		private EntityManagerInterface $entityManager,
	) {
		$this->repository = $this->entityManager->getRepository(User::class);
	}

	public function findByEmail(string $email): ?User
	{
		return $this->repository->findOneBy(['email' => $email]);
	}

    public function getById(int $id): ?User
    {
        return $this->repository->find($id);
    }

	public function save(User $user): void
	{
		$this->entityManager->persist($user);
		$this->entityManager->flush();
	}

	public function getEntityManager(): EntityManagerInterface
	{
		return $this->entityManager;
	}

    public function createQueryBuilder(string $alias = 'u', ?string $indexBy = null): \Doctrine\ORM\QueryBuilder
    {
        return $this->repository->createQueryBuilder($alias, $indexBy);
    }
}
