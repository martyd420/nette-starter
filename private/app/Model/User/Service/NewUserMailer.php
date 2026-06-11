<?php

declare(strict_types=1);

namespace App\Model\User\Service;

use App\Model\Factory\MailerFactory;
use App\Model\User\Entity\User;
use Nette\Mail\Message;

class NewUserMailer
{
	public function __construct(
		private MailerFactory $mailerFactory,
	) {
	}

	public function send(User $user): void
	{
		$mail = new Message();
		$mail->setFrom($this->mailerFactory->getFromEmail())
			->addTo($user->email)
			->setSubject('Welcome to our application')
			->setBody("Hello,\n\nthank you for registering in our application.");

		$this->mailerFactory->create()->send($mail);
	}
}
