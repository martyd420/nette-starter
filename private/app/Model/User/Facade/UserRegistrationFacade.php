<?php

declare(strict_types=1);

namespace App\Model\User\Facade;

use App\Model\User\Entity\Address;
use App\Model\User\Entity\User;
use App\Model\User\Entity\UserProfile;
use App\Model\User\Enum\AddressType;
use App\Model\User\Event\UserRegisteredEvent;
use App\Model\User\Exception\DuplicateEmailException;
use App\Model\User\Repository\UserRepository;
use App\Model\User\Service\PasswordService;
use Psr\EventDispatcher\EventDispatcherInterface;

class UserRegistrationFacade
{
	public function __construct(
		private UserRepository $userRepository,
		private PasswordService $passwordService,
		private EventDispatcherInterface $eventDispatcher,
	) {
	}

	/** @throws DuplicateEmailException */
	public function register(RegistrationData $data): User
	{
		if ($this->userRepository->findByEmail($data->email)) {
			throw new DuplicateEmailException("Email {$data->email} already exists.");
		}

		$user = new User($data->email, $this->passwordService->hash($data->password));

		$em = $this->userRepository->getEntityManager();
		$em->wrapInTransaction(function () use ($em, $user, $data): void {
			$em->persist($user);

			$profile = new UserProfile($user);
			$profile->firstName = $data->firstName;
			$profile->lastName = $data->lastName;
			$user->setProfile($profile);
			$em->persist($profile);

			$em->persist(new Address($user, AddressType::Billing, $data->street, $data->city, $data->zip));
		});

		$this->eventDispatcher->dispatch(new UserRegisteredEvent($user));

		return $user;
	}
}
