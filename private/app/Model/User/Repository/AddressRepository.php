<?php

declare(strict_types=1);

namespace App\Model\User\Repository;

use App\Model\User\Entity\Address;
use Doctrine\ORM\EntityManagerInterface;

class AddressRepository
{
	public function __construct(
		private EntityManagerInterface $entityManager,
	) {
	}

	public function getById(int $id): ?Address
	{
		return $this->entityManager->find(Address::class, $id);
	}

	public function save(Address $address): void
	{
		$this->entityManager->persist($address);
		$this->entityManager->flush();
	}
}
