<?php

declare(strict_types=1);

namespace App\Model\User\Facade;

use App\Model\User\Entity\User;
use App\Model\User\Entity\UserGroup;
use App\Model\User\Entity\UserProfile;
use App\Model\User\Enum\UserRole;
use App\Model\User\Enum\UserStatus;
use App\Model\User\Repository\UserRepository;

class UserAdminFacade
{
	public function __construct(
		private UserRepository $userRepository,
	) {
	}

    /** @param array<string, mixed> $data */
    public function updateUser(int $id, array $data): void
    {
        $user = $this->userRepository->getById($id);
        if (!$user) {
            throw new \Exception("User not found");
        }

        $user->email = $data['email'];
        $user->roles = [UserRole::from($data['role'])];
        $user->status = UserStatus::from($data['status']);

        $profile = $user->getProfile();
        if (!$profile) {
            $profile = new UserProfile($user);
            $this->userRepository->getEntityManager()->persist($profile);
        }

        $profile->firstName = $data['firstName'];
        $profile->lastName = $data['lastName'];

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
