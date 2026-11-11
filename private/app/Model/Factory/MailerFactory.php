<?php

declare(strict_types=1);

namespace App\Model\Factory;

use Nette\Mail\Mailer;
use Nette\Mail\SendmailMailer;
use Nette\Mail\SmtpMailer;
use Nette\SmartObject;

class MailerFactory
{
	use SmartObject;

	/** @param array<string, mixed> $config */
	public function __construct(
		private array $config,
	) {
	}

	public function create(): Mailer
	{
		if ($this->config['developmentMode'] && $this->config['sendEmailsInDevMode'] === false) {
			return new class () implements Mailer {
				public function send(\Nette\Mail\Message $mail): void
				{
					// do nothing in development mode
				}
			};
		}

		$smtp = $this->config['smtp'] ?? [];
		if (isset($smtp['host'])) {
			// SmtpMailer requires strings for credentials; an SMTP relay without
			// authentication (e.g. Mailpit) leaves them null in the config.
			return new SmtpMailer(
				host: $smtp['host'],
				username: $smtp['username'] ?? '',
				password: $smtp['password'] ?? '',
				port: isset($smtp['port']) ? (int) $smtp['port'] : null,
				encryption: $smtp['encryption'] ?? null,
			);
		}

		return new SendmailMailer();
	}

	public function getFromEmail(): string
	{
		return $this->config['from'];
	}
}
