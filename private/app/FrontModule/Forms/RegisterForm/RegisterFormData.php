<?php

namespace App\FrontModule\Forms\RegisterForm;

class RegisterFormData
{

	public function __construct(
		public string $email,
		public string $password,
		public string $nick,
		public bool $accept,
	)
	{

	}

}