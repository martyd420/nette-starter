<?php

declare(strict_types=1);

namespace App\Model\User\Facade;

final class RegistrationData
{
	public function __construct(
		public readonly string $email,
		public readonly string $password,
		public readonly string $firstName,
		public readonly string $lastName,
		public readonly string $street,
		public readonly string $city,
		public readonly string $zip,
	) {
	}
}
