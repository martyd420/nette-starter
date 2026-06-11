<?php

declare(strict_types=1);

namespace App\Model\User\Facade;

use App\Model\User\Enum\UserRole;
use App\Model\User\Enum\UserStatus;

final class UserUpdateData
{
	public function __construct(
		public readonly string $email,
		public readonly UserRole $role,
		public readonly UserStatus $status,
		public readonly ?string $firstName = null,
		public readonly ?string $lastName = null,
	) {
	}
}
