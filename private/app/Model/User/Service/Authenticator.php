<?php

declare(strict_types=1);

namespace App\Model\User\Service;

use App\Core\Logger\DatabaseLogger;
use App\Model\User\Enum\UserStatus;
use App\Model\User\Exception\UserBannedException;
use App\Model\User\Repository\UserRepository;
use Nette\Security\Authenticator as IAuthenticator;
use Nette\Security\SimpleIdentity;
use Nette\Security\AuthenticationException;

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
			throw new AuthenticationException('Uživatel nenalezen.', self::IdentityNotFound);
		}

		if (!$this->passwordService->verify($password, $userEntity->passwordHash)) {
            $this->logger->notice("User {$userEntity->id} failed to login.");
			throw new AuthenticationException('Neplatné heslo.', self::InvalidCredential);
		}

		if ($userEntity->status === UserStatus::Banned) {
            $this->logger->notice("User {$userEntity->id} is banned, login cancelled.");
			throw new UserBannedException('Uživatel má zakázaný přístup.', self::NotApproved);
		}

		$roles = array_map(fn($role) => $role, $userEntity->roles);

        $this->logger->info("User {$userEntity->id} logged in.");

		return new SimpleIdentity(
			$userEntity->id ?? 0,
			$roles,
			['email' => $userEntity->email]
		);
	}
}
