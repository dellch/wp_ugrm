<?php

class UgrmLdapConfig {

	public static $configuration = [
		'binddn'	=>	'',
		'pw'		=>	'',
		'basedn'	=>	'',
		'ldapUri'	=>	''
	];

	public static function getConfig() {
		return self::$configuration;
	}
}
