<?php

declare(strict_types=1);

namespace App\Model\User\Enum;

enum UserRole: string
{
	case Guest = 'guest';
	case Customer = 'customer';
	case Admin = 'admin';
	case SuperAdmin = 'superadmin';
}
