<?php

declare(strict_types=1);

namespace Tests\Model\User\Event;

use App\Model\User\Entity\User;
use App\Model\User\Event\UserRegisteredEvent;
use App\Model\User\Event\WelcomeEmailSubscriber;
use App\Model\User\Service\NewUserMailer;
use RuntimeException;
use Tester\Assert;
use Tracy\ILogger;

require __DIR__ . '/../../../bootstrap.php';

class WelcomeEmailSubscriberTest extends \Tester\TestCase
{
	public function testSubscribesToRegistrationEvent(): void
	{
		Assert::same(
			[UserRegisteredEvent::class => 'onUserRegistered'],
			WelcomeEmailSubscriber::getSubscribedEvents(),
		);
	}

	public function testWelcomeMailIsSent(): void
	{
		$user = new User('new@example.com', 'hash');
		$mailer = $this->createMailer(null);
		$logger = $this->createLogger();

		$subscriber = new WelcomeEmailSubscriber($mailer, $logger);
		$subscriber->onUserRegistered(new UserRegisteredEvent($user));

		Assert::same([$user], $mailer->sent);
		Assert::same([], $logger->logged);
	}

	public function testMailFailureIsSwallowedAndLogged(): void
	{
		$user = new User('new@example.com', 'hash');
		$failure = new RuntimeException('SMTP down');
		$mailer = $this->createMailer($failure);
		$logger = $this->createLogger();

		$subscriber = new WelcomeEmailSubscriber($mailer, $logger);
		// Registration must not break when the mail server is unavailable.
		Assert::noError(fn () => $subscriber->onUserRegistered(new UserRegisteredEvent($user)));

		Assert::same([[$failure, ILogger::WARNING]], $logger->logged);
	}

	/**
	 * @return NewUserMailer&object{sent: User[]}
	 */
	private function createMailer(?\Throwable $throw): NewUserMailer
	{
		return new class ($throw) extends NewUserMailer {
			/** @var User[] */
			public array $sent = [];

			public function __construct(private ?\Throwable $throw)
			{
			}

			public function send(User $user): void
			{
				if ($this->throw !== null) {
					throw $this->throw;
				}

				$this->sent[] = $user;
			}
		};
	}

	/**
	 * @return ILogger&object{logged: array<array{mixed, string}>}
	 */
	private function createLogger(): ILogger
	{
		return new class () implements ILogger {
			/** @var array<array{mixed, string}> */
			public array $logged = [];

			public function log(mixed $value, string $level = self::INFO)
			{
				$this->logged[] = [$value, $level];
			}
		};
	}
}

(new WelcomeEmailSubscriberTest())->run();
