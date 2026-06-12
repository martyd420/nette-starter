<?php

declare(strict_types=1);

namespace Tests\Model\User\Doctrine;

use App\Model\User\Doctrine\UserRolesType;
use App\Model\User\Enum\UserRole;
use Doctrine\DBAL\Platforms\SQLitePlatform;
use Tester\Assert;

require __DIR__ . '/../../../bootstrap.php';

class UserRolesTypeTest extends \Tester\TestCase
{
	private UserRolesType $type;

	private SQLitePlatform $platform;

	protected function setUp(): void
	{
		$this->type = new UserRolesType();
		$this->platform = new SQLitePlatform();
	}

	public function testConvertToDatabaseValueMapsEnumsToBackingValues(): void
	{
		$json = $this->type->convertToDatabaseValue([UserRole::Admin, UserRole::Guest], $this->platform);

		Assert::same('["admin","guest"]', $json);
	}

	public function testConvertToDatabaseValueAcceptsPlainStrings(): void
	{
		$json = $this->type->convertToDatabaseValue(['admin', UserRole::Customer], $this->platform);

		Assert::same('["admin","customer"]', $json);
	}

	public function testConvertToPHPValueRehydratesEnums(): void
	{
		$roles = $this->type->convertToPHPValue('["admin","customer"]', $this->platform);

		Assert::same([UserRole::Admin, UserRole::Customer], $roles);
	}

	public function testConvertToPHPValueNullBecomesEmptyArray(): void
	{
		Assert::same([], $this->type->convertToPHPValue(null, $this->platform));
	}

	public function testRoundTripPreservesRoles(): void
	{
		$roles = [UserRole::SuperAdmin, UserRole::Customer];

		$json = $this->type->convertToDatabaseValue($roles, $this->platform);
		$restored = $this->type->convertToPHPValue($json, $this->platform);

		Assert::same($roles, $restored);
	}
}

(new UserRolesTypeTest())->run();
