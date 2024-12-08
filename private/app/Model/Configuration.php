<?php

namespace App\Model;

/*
 *  GLOBAL CONFIGURATION
 *
 **/
class Configuration
{
	private static string $webName = 'Starter';

	public static function getWebName(): string
	{
		return self::$webName;
	}



}