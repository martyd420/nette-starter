<?php

declare(strict_types=1);

namespace App\Model\User\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class UserProfile
{
	#[ORM\Id]
	#[ORM\Column(type: 'integer')]
	#[ORM\GeneratedValue]
	public int $id;

    #[ORM\OneToOne(inversedBy: 'profile', targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    public User $user;

	#[ORM\Column(type: 'string', nullable: true)]
	public ?string $firstName = null;

	#[ORM\Column(type: 'string', nullable: true)]
	public ?string $lastName = null;

	#[ORM\Column(type: 'string', nullable: true)]
	public ?string $phone = null;

	#[ORM\Column(type: 'string', nullable: true)]
	public ?string $companyName = null;

	#[ORM\Column(type: 'string', nullable: true)]
	public ?string $vatId = null;

	public function __construct(User $user)
	{
		$this->user = $user;
	}
}
