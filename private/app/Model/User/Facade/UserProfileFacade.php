<?php

declare(strict_types=1);

namespace App\Model\User\Facade;

use App\Model\User\Entity\User;
use App\Model\User\Repository\UserRepository;
use App\Model\User\Service\PasswordService;

class UserProfileFacade
{
	public function __construct(
		private UserRepository $userRepository,
		private PasswordService $passwordService,
	) {
	}

	public function changePassword(User $user, string $newPassword): void
	{
		$user->passwordHash = $this->passwordService->hash($newPassword);
		$this->userRepository->save($user);
	}

	public function updateProfile(User $user, array $data): void
	{
		// Logika pro aktualizaci profilu a adres
	}
}
