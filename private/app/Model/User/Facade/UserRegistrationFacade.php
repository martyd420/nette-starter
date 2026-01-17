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
use App\Model\User\Service\NewUserMailer;
use App\Model\User\Service\PasswordService;

class UserRegistrationFacade
{
	public function __construct(
		private UserRepository $userRepository,
		private AddressRepository $addressRepository,
		private PasswordService $passwordService,
		private NewUserMailer $newUserMailer,
	) {
	}

	/**
	 * @throws DuplicateEmailException
	 * @throws \Exception
	 */
	public function register(RegistrationData $data): User
	{
		if ($this->userRepository->findByEmail($data->email)) {
			throw new DuplicateEmailException("Email {$data->email} already exists.");
		}

		$passwordHash = $this->passwordService->hash($data->password);
		$user = new User($data->email, $passwordHash);

		$em = $this->userRepository->getEntityManager();
		$em->beginTransaction();

		try {
			$this->userRepository->save($user);

			$profile = new UserProfile($user);
			$profile->firstName = $data->firstName;
			$profile->lastName = $data->lastName;
			$em->persist($profile);

			$address = new Address(
				$user,
				AddressType::Billing,
				$data->street,
				$data->city,
				$data->zip
			);
			$this->addressRepository->save($address);

			$em->commit();
		} catch (\Exception $e) {
			$em->rollback();

			throw $e;
		}


		try {
			$this->newUserMailer->send($user);
		} catch (\Exception $e) {
			bdump('Cant send email: ' . $e->getMessage());

			throw $e;
		}

		return $user;
	}
}
