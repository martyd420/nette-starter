<?php

declare(strict_types=1);

namespace App\Model\User\Entity;

use App\Model\User\Doctrine\UserRolesType;
use App\Model\User\Enum\UserRole;
use App\Model\User\Enum\UserStatus;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: '`user`')]
class User
{
	#[ORM\Id]
	#[ORM\Column(type: 'integer')]
	#[ORM\GeneratedValue]
	public int $id;

	#[ORM\Column(type: 'string', unique: true)]
	public string $email;

	#[ORM\Column(type: 'string')]
	public string $passwordHash;

	/** @var UserRole[] */
	#[ORM\Column(type: UserRolesType::Name)]
	public array $roles = [];

	#[ORM\Column(type: 'string', enumType: UserStatus::class)]
	public UserStatus $status;

	#[ORM\Column(type: 'datetime_immutable')]
	public DateTimeImmutable $createdAt;

	#[ORM\ManyToOne(targetEntity: UserGroup::class)]
	#[ORM\JoinColumn(nullable: true)]
	public ?UserGroup $group = null;

	#[ORM\OneToOne(mappedBy: 'user', targetEntity: UserProfile::class)]
	public ?UserProfile $profile = null;

	/** @var Collection<int, Address> */
	#[ORM\OneToMany(mappedBy: 'user', targetEntity: Address::class, cascade: ['persist', 'remove'])]
	public Collection $addresses;

	public function __construct(string $email, string $passwordHash, UserRole $role = UserRole::Customer)
	{
		$this->email = $email;
		$this->passwordHash = $passwordHash;
		$this->roles = [$role];
		$this->status = UserStatus::Active;
		$this->createdAt = new DateTimeImmutable();
		$this->addresses = new ArrayCollection();
	}

	public function getName(): string
	{
		return $this->profile?->firstName . ' ' . $this->profile?->lastName;
	}
}
