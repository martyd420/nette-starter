<?php

namespace App\FrontModule\Forms\LoginForm;

class LoginFormData
{

	public function __construct(
		public string $email,
		public string $password,
	)
	{

	}

}