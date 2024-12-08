<?php

namespace App\Model;

use App\Model\Entity\User;
use Nette;
use Nette\Security\Passwords;
use Nette\Security\SimpleIdentity;
use Nettrine\ORM\Decorator\SimpleEntityManagerDecorator;


class Authenticator implements Nette\Security\Authenticator
{
	private SimpleEntityManagerDecorator $em;

	private Passwords $passwords;

	public function __construct(SimpleEntityManagerDecorator $em, Passwords $p) {
		$this->em = $em;
		$this->passwords = $p;
	}

	public function authenticate(string $email, string $password): SimpleIdentity
	{
		$user = $this->em->getRepository(User::class)->findOneBy([
			'email' => $email,
		]);

		if(!$user || !$this->passwords->verify($password, $user->getPassword())) {
			throw new Nette\Security\AuthenticationException('Invalid credentials.');
		}

		return new SimpleIdentity(
			$user->getId(),
			$user->getRoles(),
			[
				'email' => $user->getEmail(),
				'nick' => $user->getNick(),
			],
		);
	}
}