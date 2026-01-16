<?php

declare(strict_types=1);

namespace App\Model\User\Enum;

enum AddressType: string
{
	case Billing = 'billing';
	case Delivery = 'delivery';
}
