<?php

declare(strict_types=1);

namespace App\Model\Factory;

use Nette\Mail\IMailer;
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

	public function create(): IMailer
	{
		if ($this->config['developmentMode'] && $this->config['sendEmailsInDevMode'] === false) {
			return new class implements IMailer {
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
