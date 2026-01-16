<?php

declare(strict_types=1);

namespace App\Model\User\Service;

use App\Model\User\Exception\UserBannedException;
use App\Model\User\Enum\UserStatus;
use App\Model\User\Repository\UserRepository;
use Nette\Security\Authenticator as IAuthenticator;
use Nette\Security\SimpleIdentity;
use Nette\Security\AuthenticationException;

class Authenticator implements IAuthenticator
{
	public function __construct(
		private UserRepository $userRepository,
		private PasswordService $passwordService,
	) {
	}

	public function authenticate(string $user, string $password): SimpleIdentity
	{
		$userEntity = $this->userRepository->findByEmail($user);

		if (!$userEntity) {
			throw new AuthenticationException('Uživatel nenalezen.', self::IdentityNotFound);
		}

		if (!$this->passwordService->verify($password, $userEntity->passwordHash)) {
			throw new AuthenticationException('Neplatné heslo.', self::InvalidCredential);
		}

		if ($userEntity->status === UserStatus::Banned) {
			throw new AuthenticationException('Uživatel má zakázaný přístup.', self::NotApproved);
		}

        bdump($userEntity->roles);
		$roles = array_map(fn($role) => $role, $userEntity->roles);

		return new SimpleIdentity(
			$userEntity->id ?? 0,
			$roles,
			['email' => $userEntity->email]
		);
	}
}
