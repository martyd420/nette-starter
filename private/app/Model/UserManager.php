<?php

namespace App\Model;

use App\Model\Entity\User;
use Nette\Security\Passwords;
use Nettrine\ORM\Decorator\SimpleEntityManagerDecorator;

class UserManager
{

	private SimpleEntityManagerDecorator $em;
	private Passwords $passwords;

	public function __construct(SimpleEntityManagerDecorator $em, Passwords $passwords)
	{
		$this->em = $em;
		$this->passwords = $passwords;
	}


	public function createUser($email, $password, $nick = null): void
	{
		$user = new User();
		$user->setEmail($email);
		$user->setPassword($this->passwords->hash($password));
		$user->setNick($nick);

		$this->em->persist($user);
		$this->em->flush();
	}



}
