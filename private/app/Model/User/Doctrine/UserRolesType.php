<?php

declare(strict_types=1);

namespace App\Model\User\Doctrine;

use App\Model\User\Enum\UserRole;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\JsonType;

/**
 * Persists a list of UserRole enums as a JSON array of their backing values,
 * so User::$roles holds UserRole instances even after hydration from the database.
 */
final class UserRolesType extends JsonType
{
	public const Name = 'user_roles';

	/** @return UserRole[] */
	public function convertToPHPValue(mixed $value, AbstractPlatform $platform): array
	{
		$decoded = parent::convertToPHPValue($value, $platform);
		if (!is_array($decoded)) {
			return [];
		}

		return array_map(static fn (string $role): UserRole => UserRole::from($role), $decoded);
	}

	public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?string
	{
		if (is_array($value)) {
			$value = array_map(
				static fn (UserRole|string $role): string => $role instanceof UserRole ? $role->value : $role,
				$value,
			);
		}

		return parent::convertToDatabaseValue($value, $platform);
	}
}
