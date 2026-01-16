<?php

declare(strict_types=1);

namespace App\Model\User\Facade;

use App\Model\User\Entity\Address;
use App\Model\User\Entity\User;
use App\Model\User\Entity\UserProfile;
use App\Model\User\Enum\AddressType;
use App\Model\User\Exception\DuplicateEmailException;
use App\Model\User\Repository\AddressRepository;
use App\Model\User\Repository\UserRepository;
use App\Model\User\Service\PasswordService;

class UserRegistrationFacade
{
	public function __construct(
		private UserRepository $userRepository,
		private AddressRepository $addressRepository,
		private PasswordService $passwordService,
	) {
	}

	public function register(RegistrationData $data): User
	{
		if ($this->userRepository->findByEmail($data->email)) {
			throw new DuplicateEmailException("Email {$data->email} již existuje.");
		}

		$passwordHash = $this->passwordService->hash($data->password);
		$user = new User($data->email, $passwordHash);
		
		// V reálné aplikaci by zde byla transakce
		$this->userRepository->getEntityManager()->beginTransaction();
		try {
			$this->userRepository->save($user);

			$profile = new UserProfile($user);
			$profile->firstName = $data->firstName;
			$profile->lastName = $data->lastName;
			$this->userRepository->getEntityManager()->persist($profile);

			$address = new Address(
				$user,
				AddressType::Billing,
				$data->street,
				$data->city,
				$data->zip
			);
			$this->addressRepository->save($address);
			
			$this->userRepository->getEntityManager()->commit();
		} catch (\Exception $e) {
			$this->userRepository->getEntityManager()->rollback();
			throw $e;
		}

		return $user;
	}
}
