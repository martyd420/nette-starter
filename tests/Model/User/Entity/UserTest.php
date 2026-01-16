<?php

declare(strict_types=1);

namespace Tests\Model\User\Entity;

use App\Model\User\Entity\User;
use App\Model\User\Entity\UserProfile;
use Tester\Assert;

require __DIR__ . '/../../../bootstrap.php';

class UserTest extends \Tester\TestCase
{
	public function testGetName(): void
	{
		$user = new User('test@example.com', 'hash');

		Assert::same(' ', $user->getName());

		$profile = new UserProfile($user);
		$profile->firstName = 'John';
		$profile->lastName = 'Doe';
		$user->setProfile($profile);

		Assert::same('John Doe', $user->getName());
	}
}

(new UserTest())->run();
