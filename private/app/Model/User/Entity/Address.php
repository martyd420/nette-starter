<?php

declare(strict_types=1);

namespace App\Model\User\Entity;

use App\Model\User\Enum\AddressType;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Address
{
	#[ORM\Id]
	#[ORM\Column(type: 'integer')]
	#[ORM\GeneratedValue]
	public int $id;

	#[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'addresses')]
	#[ORM\JoinColumn(nullable: false)]
	public User $user;

	#[ORM\Column(type: 'string', enumType: AddressType::class)]
	public AddressType $type;

	#[ORM\Column(type: 'string')]
	public string $street;

	#[ORM\Column(type: 'string')]
	public string $city;

	#[ORM\Column(type: 'string')]
	public string $zip;

	#[ORM\Column(type: 'string')]
	public string $country;

	public function __construct(User $user, AddressType $type, string $street, string $city, string $zip, string $country = 'CZ')
	{
		$this->user = $user;
		$this->type = $type;
		$this->street = $street;
		$this->city = $city;
		$this->zip = $zip;
		$this->country = $country;
	}
}
