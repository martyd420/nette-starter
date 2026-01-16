<?php

declare(strict_types=1);

namespace App\Model\Fixtures;

use App\Model\User\Entity\Address;
use App\Model\User\Entity\User;
use App\Model\User\Entity\UserProfile;
use App\Model\User\Enum\AddressType;
use App\Model\User\Enum\UserRole;
use App\Model\User\Service\PasswordService;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class UserFixture extends AbstractFixture
{
	public function __construct(
		private PasswordService $passwordService,
	) {
	}

	public function load(ObjectManager $manager): void
	{
		$faker = Factory::create('cs_CZ');

		$admin = new User(
			'starter@pcdr.cz',
			$this->passwordService->hash('123456'),
			UserRole::Admin
		);

		$manager->persist($admin);

		$adminProfile = new UserProfile($admin);
		$adminProfile->firstName = 'Admin';
		$adminProfile->lastName = 'Starter';
		$manager->persist($adminProfile);

        // Addresses for Admin
        for ($j = 0; $j < rand(1, 2); $j++) {
            $address = new Address(
                $admin,
                $j === 0 ? AddressType::Billing : AddressType::Delivery,
                $faker->streetAddress(),
                $faker->city(),
                $faker->postcode(),
                'CZ'
            );
            $manager->persist($address);
        }

		for ($i = 1; $i <= 5; $i++) {
			$user = new User(
				$faker->unique()->safeEmail(),
				$this->passwordService->hash('password' . $i),
				UserRole::Customer
			);
			$manager->persist($user);

			$profile = new UserProfile($user);
			$profile->firstName = $faker->firstName();
			$profile->lastName = $faker->lastName();
			$profile->phone = $faker->phoneNumber();
			$manager->persist($profile);

            // Addresses for User
            for ($j = 0; $j < rand(1, 2); $j++) {
                $address = new Address(
                    $user,
                    $j === 0 ? AddressType::Billing : AddressType::Delivery,
                    $faker->streetAddress(),
                    $faker->city(),
                    $faker->postcode(),
                    'CZ'
                );
                $manager->persist($address);
            }
		}

		$manager->flush();
	}
}
