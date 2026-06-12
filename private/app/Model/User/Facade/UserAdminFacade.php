<?php

declare(strict_types=1);

namespace App\Model\User\Facade;

use App\Model\User\Entity\User;
use App\Model\User\Entity\UserGroup;
use App\Model\User\Entity\UserProfile;
use App\Model\User\Enum\UserStatus;
use App\Model\User\Exception\UserNotFoundException;
use App\Model\User\Repository\UserRepository;

class UserAdminFacade
{
	public function __construct(
		private UserRepository $userRepository,
	) {
	}

	/** @throws UserNotFoundException */
	public function updateUser(int $id, UserUpdateData $data): void
	{
		$user = $this->userRepository->getById($id);
		if (!$user) {
			throw new UserNotFoundException("User $id not found.");
		}

		$user->email = $data->email;
		$user->roles = [$data->role];
		$user->status = $data->status;

		$profile = $user->profile;
		if (!$profile) {
			$profile = new UserProfile($user);
			$user->profile = $profile;
			$this->userRepository->getEntityManager()->persist($profile);
		}

		$profile->firstName = $data->firstName;
		$profile->lastName = $data->lastName;

		$this->userRepository->save($user);
	}

	public function banUser(User $user): void
	{
		$user->status = UserStatus::Banned;
		$this->userRepository->save($user);
	}

	public function setUserGroup(User $user, ?UserGroup $group): void
	{
		$user->group = $group;
		$this->userRepository->save($user);
	}
}
