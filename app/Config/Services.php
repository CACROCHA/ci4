<?php

namespace Config;

use CodeIgniter\Config\BaseService;

/**
 * Services Configuration file.
 *
 * Services are simply other classes/libraries that the system uses
 * to do its job. This is used by CodeIgniter to allow the core of the
 * framework to be swapped out easily without affecting the usage within
 * the rest of your application.
 *
 * This file holds any application-specific services, or service overrides
 * that you might need. An example has been included with the general
 * method format you should use for your service methods. For more examples,
 * see the core Services file at system/Config/Services.php.
 */
class Services extends BaseService
{
    /*
     * public static function example($getShared = true)
     * {
     *     if ($getShared) {
     *         return static::getSharedInstance('example');
     *     }
     *
     *     return new \CodeIgniter\Example();
     * }
     */
	public static function getLocalDir()
	{
		return getenv('CACHE_DIR');
	}
    
	public static function validarBase64(string $valor)
	{
		// Check if there are valid base64 characters
		if (!preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $valor)) return false;

		// Decode the string in strict mode and check the results
		$decoded = base64_decode($valor, true);
		if(!$decoded) return false;

		// if string returned contains not printable chars
		if (0 < preg_match('/((?![[:graph:]])(?!\s)(?!\p{L}))./', $decoded, $matched)) return false;

		// Encode the string again
		if(base64_encode($decoded) != $valor) return false;

		return true;
	}

}
