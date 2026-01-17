<?php

declare(strict_types=1);

namespace App\Model\User\Service;

use App\Model\Factory\MailerFactory;
use App\Model\User\Entity\User;
use Nette\Mail\Message;
use Nette\SmartObject;

class NewUserMailer
{
	use SmartObject;

	public function __construct(
		private MailerFactory $mailerFactory,
	) {
	}

	public function send(User $user): void
	{
		$mail = new Message();
		$mail->setFrom($this->mailerFactory->getFromEmail())
			->addTo($user->email)
			->setSubject('Vítejte v naší aplikaci')
			->setBody("Dobrý den,\n\nděkujeme za registraci v naší aplikaci.");

		$this->mailerFactory->create()->send($mail);
	}
}
