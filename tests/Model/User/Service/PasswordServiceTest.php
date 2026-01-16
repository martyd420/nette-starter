<?php

declare(strict_types=1);

namespace Tests\Model\User\Service;

use App\Model\User\Service\PasswordService;
use Nette\Security\Passwords;
use Tester\Assert;

require __DIR__ . '/../../../bootstrap.php';

class PasswordServiceTest extends \Tester\TestCase
{
	private PasswordService $service;

	protected function setUp(): void
	{
		$this->service = new PasswordService(new Passwords());
	}

	public function testHashAndVerify(): void
	{
		$password = 'secret123';
		$hash = $this->service->hash($password);

		Assert::notSame($password, $hash);
		Assert::true($this->service->verify($password, $hash));
		Assert::false($this->service->verify('wrong-password', $hash));
	}
}

(new PasswordServiceTest())->run();
