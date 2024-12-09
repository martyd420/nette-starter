<?php

namespace App\Model;

use App\Model\Entity\User;
use Nette\Security\Passwords;
use Nette\Utils\ArrayHash;
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

	public function getRepository(): \Doctrine\ORM\EntityRepository
	{
		return $this->em->getRepository(User::class);
	}

	public function updateUser(int $id, ArrayHash $values): User
	{
		$user = $this->em->getRepository(User::class)->find($id);
		if(!$user) {
			throw new \Exception('User not found');
		}
		$user->setEmail($values->email);
		$user->setNick($values->nick);
		$user->setActive((int)$values->active);

		$this->em->persist($user);
		$this->em->flush();

		return $user;
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
