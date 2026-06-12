<?php

declare(strict_types=1);

namespace Tests\Model\User\Service;

use App\Model\User\Entity\User;
use App\Model\User\Enum\UserRole;
use App\Model\User\Enum\UserStatus;
use App\Model\User\Exception\UserBannedException;
use App\Model\User\Repository\UserRepository;
use App\Model\User\Service\Authenticator;
use App\Model\User\Service\PasswordService;
use Nette\Security\AuthenticationException;
use Nette\Security\Passwords;
use Psr\Log\NullLogger;
use Tester\Assert;

require __DIR__ . '/../../../bootstrap.php';

class AuthenticatorTest extends \Tester\TestCase
{
	private PasswordService $passwordService;

	protected function setUp(): void
	{
		$this->passwordService = new PasswordService(new Passwords());
	}

	public function testUnknownUserIsRejected(): void
	{
		$authenticator = $this->createAuthenticator(null);

		Assert::exception(
			fn () => $authenticator->authenticate('nobody@example.com', 'secret'),
			AuthenticationException::class,
		);
	}

	public function testWrongPasswordIsRejected(): void
	{
		$user = $this->createUser('secret123');
		$authenticator = $this->createAuthenticator($user);

		Assert::exception(
			fn () => $authenticator->authenticate($user->email, 'wrong-password'),
			AuthenticationException::class,
		);
	}

	public function testBannedUserIsRejected(): void
	{
		$user = $this->createUser('secret123');
		$user->status = UserStatus::Banned;
		$authenticator = $this->createAuthenticator($user);

		Assert::exception(
			fn () => $authenticator->authenticate($user->email, 'secret123'),
			UserBannedException::class,
		);
	}

	public function testValidLoginReturnsIdentityWithStringRoles(): void
	{
		$user = $this->createUser('secret123', UserRole::Admin);
		$authenticator = $this->createAuthenticator($user);

		$identity = $authenticator->authenticate($user->email, 'secret123');

		Assert::same($user->id, $identity->getId());
		Assert::same(['admin'], $identity->getRoles());
		Assert::same($user->email, $identity->getData()['email']);
	}

	private function createUser(string $password, UserRole $role = UserRole::Customer): User
	{
		$user = new User('user@example.com', $this->passwordService->hash($password), $role);
		$user->id = 42;

		return $user;
	}

	private function createAuthenticator(?User $user): Authenticator
	{
		$repository = new class ($user) extends UserRepository {
			public function __construct(private ?User $user)
			{
			}

			public function findByEmail(string $email): ?User
			{
				return $this->user;
			}
		};

		return new Authenticator($repository, $this->passwordService, new NullLogger());
	}
}

(new AuthenticatorTest())->run();
