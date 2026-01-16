<?php

declare(strict_types=1);

namespace App\Model\User\Service;

use Nette\Security\Passwords;

class PasswordService
{
	public function __construct(
		private Passwords $passwords,
	) {
	}

	public function hash(string $password): string
	{
		return $this->passwords->hash($password);
	}

	public function verify(string $password, string $hash): bool
	{
		return $this->passwords->verify($password, $hash);
	}
}
