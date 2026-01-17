<?php

declare(strict_types=1);

namespace App\Model\Factory;

use Nette\Mail\Mailer;
use Nette\Mail\SmtpMailer;
use Nette\Mail\SendmailMailer;
use Nette\SmartObject;

class MailerFactory
{
	use SmartObject;

	public function __construct(
		private array $config,
	) {
	}

	public function create(): Mailer
	{
		if ($this->config['developmentMode'] && $this->config['sendEmailsInDevMode'] === false) {
			return new class implements Mailer {
				public function send(\Nette\Mail\Message $mail): void
				{
					// do nothing in development mode
				}
			};
		}

		if (isset($this->config['smtp']['host']) && $this->config['smtp']['host'] !== null) {
			return new SmtpMailer(...$this->config['smtp']);
		}

		return new SendmailMailer();
	}

	public function getFromEmail(): string
	{
		return $this->config['from'];
	}
}
