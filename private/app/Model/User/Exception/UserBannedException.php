<?php

declare(strict_types=1);

namespace App\Model\User\Exception;

use Nette\Security\AuthenticationException;

class UserBannedException extends AuthenticationException
{
}
