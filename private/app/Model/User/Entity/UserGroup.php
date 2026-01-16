<?php

declare(strict_types=1);

namespace App\Model\User\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class UserGroup
{
	#[ORM\Id]
	#[ORM\Column(type: 'integer')]
	#[ORM\GeneratedValue]
	public int $id;

	#[ORM\Column(type: 'string')]
	public string $name;

	/** @var float 5% = 0.05 */
	#[ORM\Column(type: 'float')]
	public float $defaultDiscount;

	/**
	 * @var array<string, float> e.g. ["category" => 0.15, "others" => 0.05]
	 */
	#[ORM\Column(type: 'json')]
	public array $categoryDiscounts = [];

	public function __construct(string $name, float $defaultDiscount = 0.0)
	{
		$this->name = $name;
		$this->defaultDiscount = $defaultDiscount;
	}
}
