<?php

namespace App\Model\Entity;

use App\Model\Roles;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;

#[ORM\Entity]
#[ORM\Index(columns: ['email'], name: 'email')]
#[ORM\Table(name: 'user')]
class User
{
	#[Id, Column(type: "integer", options: ["unsigned" => true]), GeneratedValue(strategy: "IDENTITY")]
	protected int $id;

	#[ORM\Column(length: 512, nullable: true)]
	protected ?string $roles = null;

	#[ORM\Column(length: 256, unique: true)]
	protected string $email;

	#[ORM\Column(length: 256, nullable: true)]
	protected ?string $nick = null;

	#[ORM\Column(length: 128)]
	protected string $password;

	#[ORM\Column(length: 128, nullable: true)]
	protected ?string $password_recovery_token = null;

	#[ORM\Column(type: "boolean", options: ["default" => true])]
	protected bool $active = true;

	public function isAdmin(): bool
	{
		return str_contains($this->roles, Roles::ROLE_ADMIN);
	}

	public function getId(): int
	{
		return $this->id;
	}

	public function setId(int $id): void
	{
		$this->id = $id;
	}

	public function getRoles(): ?string
	{
		return $this->roles;
	}

	public function setRoles(?string $roles): void
	{
		$this->roles = $roles;
	}

	public function getEmail(): string
	{
		return $this->email;
	}

	public function setEmail(string $email): void
	{
		$this->email = $email;
	}

	public function getNick(): ?string
	{
		return $this->nick;
	}

	public function setNick(?string $nick): void
	{
		$this->nick = $nick;
	}

	public function getPassword(): string
	{
		return $this->password;
	}

	public function setPassword(string $password): void
	{
		$this->password = $password;
	}

	public function getPasswordRecoveryToken(): ?string
	{
		return $this->password_recovery_token;
	}

	public function setPasswordRecoveryToken(?string $password_recovery_token): void
	{
		$this->password_recovery_token = $password_recovery_token;
	}

	public function isActive(): bool
	{
		return $this->active;
	}

	public function setActive(bool $active): void
	{
		$this->active = $active;
	}




}