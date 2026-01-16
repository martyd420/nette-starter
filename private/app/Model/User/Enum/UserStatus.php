<?php

declare(strict_types=1);

namespace App\Model\User\Enum;

enum UserStatus: string
{
	case Active = 'active';
	case Banned = 'banned';
	case Unconfirmed = 'unconfirmed';
}
