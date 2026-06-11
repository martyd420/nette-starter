<?php

declare(strict_types=1);

namespace App\Model\User\Service;

use App\Core\Logger\DatabaseLogger;
use App\Model\User\Enum\UserRole;
use App\Model\User\Enum\UserStatus;
use App\Model\User\Exception\UserBannedException;
use App\Model\User\Repository\UserRepository;
use Nette\Security\AuthenticationException;
use Nette\Security\Authenticator as IAuthenticator;
use Nette\Security\SimpleIdentity;

class Authenticator implements IAuthenticator
{
	public function __construct(
		private UserRepository $userRepository,
		private PasswordService $passwordService,
		private DatabaseLogger $logger,
	) {
	}

	/**
	 * @throws AuthenticationException
	 * @throws UserBannedException
	 */
	public function authenticate(string $username, string $password): SimpleIdentity
	{
		$userEntity = $this->userRepository->findByEmail($username);

		if (!$userEntity) {
			throw new AuthenticationException('User not found.', self::IdentityNotFound);
		}

		if (!$this->passwordService->verify($password, $userEntity->passwordHash)) {
			$this->logger->notice("User {$userEntity->id} failed to login.");

			throw new AuthenticationException('Invalid password.', self::InvalidCredential);
		}

		if ($userEntity->status === UserStatus::Banned) {
			$this->logger->notice("User {$userEntity->id} is banned, login cancelled.");

			throw new UserBannedException('User is banned.', self::NotApproved);
		}

		$this->logger->info("User {$userEntity->id} logged in.");

		return new SimpleIdentity(
			$userEntity->id,
			array_map(static fn (UserRole $role): string => $role->value, $userEntity->roles),
			['email' => $userEntity->email]
		);
	}
}
