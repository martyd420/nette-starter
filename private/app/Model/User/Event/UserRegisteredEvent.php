<?php

declare(strict_types=1);

namespace App\Model\User\Event;

use App\Model\User\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

final class UserRegisteredEvent extends Event
{
	public function __construct(
		public readonly User $user,
	) {
	}
}
