<?php

declare(strict_types=1);

namespace Tests\Model\User\Facade;

use App\Model\User\Entity\User;
use App\Model\User\Exception\DuplicateEmailException;
use App\Model\User\Facade\RegistrationData;
use App\Model\User\Facade\UserRegistrationFacade;
use App\Model\User\Repository\UserRepository;
use App\Model\User\Service\PasswordService;
use Nette\Security\Passwords;
use Psr\EventDispatcher\EventDispatcherInterface;
use Tester\Assert;

require __DIR__ . '/../../../bootstrap.php';

class UserRegistrationFacadeTest extends \Tester\TestCase
{
	public function testDuplicateEmailIsRejectedBeforePersisting(): void
	{
		$existing = new User('taken@example.com', 'hash');
		$repository = new class ($existing) extends UserRepository {
			public function __construct(private User $existing)
			{
			}

			public function findByEmail(string $email): ?User
			{
				return $this->existing;
			}
		};

		$dispatcher = new class () implements EventDispatcherInterface {
			public bool $dispatched = false;

			public function dispatch(object $event): object
			{
				$this->dispatched = true;

				return $event;
			}
		};

		$facade = new UserRegistrationFacade(
			$repository,
			new PasswordService(new Passwords()),
			$dispatcher,
		);

		Assert::exception(
			fn () => $facade->register($this->sampleData('taken@example.com')),
			DuplicateEmailException::class,
		);
		Assert::false($dispatcher->dispatched);
	}

	private function sampleData(string $email): RegistrationData
	{
		return new RegistrationData(
			email: $email,
			password: 'secret123',
			firstName: 'John',
			lastName: 'Doe',
			street: 'Main 1',
			city: 'Town',
			zip: '12345',
		);
	}
}

(new UserRegistrationFacadeTest())->run();
