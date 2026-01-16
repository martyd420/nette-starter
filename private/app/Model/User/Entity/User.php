<?php

declare(strict_types=1);

namespace App\Model\User\Entity;

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
	#[ORM\Column(type: 'json')]
	public array $roles = [];

	#[ORM\Column(type: 'string', enumType: UserStatus::class)]
	public UserStatus $status;

	#[ORM\Column(type: 'datetime_immutable')]
	public DateTimeImmutable $createdAt;

	#[ORM\ManyToOne(targetEntity: UserGroup::class)]
	#[ORM\JoinColumn(nullable: true)]
	public ?UserGroup $group = null;

    #[ORM\OneToOne(mappedBy: 'user', targetEntity: UserProfile::class)]
    private ?UserProfile $profile = null;

    public function setProfile(?UserProfile $profile): void
    {
        $this->profile = $profile;
    }

	/** @var Collection<int, Address> */
	#[ORM\OneToMany(mappedBy: 'user', targetEntity: Address::class, cascade: ['persist', 'remove'])]
	private Collection $addresses;


    public function __construct(string $email, string $passwordHash, UserRole $role = UserRole::Customer)
    {
        $this->email = $email;
        $this->passwordHash = $passwordHash;
        $this->roles = [$role];
        $this->status = UserStatus::Active;
        $this->createdAt = new DateTimeImmutable();
		$this->addresses = new ArrayCollection();
    }

    public function getProfile(): ?UserProfile
    {
        return $this->profile;
    }

    public function getName(): string
    {
        return $this->getProfile()?->firstName . ' ' . $this->getProfile()?->lastName;
    }

	/** @return Collection<int, Address> */
	public function getAddresses(): Collection
	{
		return $this->addresses;
	}


}