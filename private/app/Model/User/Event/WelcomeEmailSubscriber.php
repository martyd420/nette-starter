<?php

declare(strict_types=1);

namespace App\Model\User\Event;

use App\Model\User\Service\NewUserMailer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Tracy\ILogger;

final class WelcomeEmailSubscriber implements EventSubscriberInterface
{
	public function __construct(
		private NewUserMailer $mailer,
		private ILogger $logger,
	) {
	}

	public static function getSubscribedEvents(): array
	{
		return [UserRegisteredEvent::class => 'onUserRegistered'];
	}

	public function onUserRegistered(UserRegisteredEvent $event): void
	{
		try {
			$this->mailer->send($event->user);
		} catch (\Throwable $e) {
			// A mail outage must not break the registration itself.
			$this->logger->log($e, ILogger::WARNING);
		}
	}
}
