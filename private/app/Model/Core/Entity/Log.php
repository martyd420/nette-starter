<?php

declare(strict_types=1);

namespace App\Model\Core\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'log')]
class Log
{
	#[ORM\Id]
	#[ORM\Column(type: 'integer')]
	#[ORM\GeneratedValue]
	public int $id;

	#[ORM\Column(type: 'datetime_immutable')]
	public DateTimeImmutable $createdAt;

	#[ORM\Column(type: 'string')]
	public string $level;

	#[ORM\Column(type: 'text')]
	public string $message;

	#[ORM\Column(type: 'text', nullable: true)]
	public ?string $context = null;

	#[ORM\Column(type: 'string', nullable: true)]
	public ?string $source = null;

	public function __construct(string $level, string $message)
	{
		$this->level = $level;
		$this->message = $message;
		$this->createdAt = new DateTimeImmutable();
	}
}
