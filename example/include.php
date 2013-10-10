<?php
require_once __DIR__ . '/../vendor/autoload.php';

/*
 * Config variables
 */

$regwebClientId		= 'regweb_testclient';
$regwebClientSecret	= 'regweb_clientsecret';
$regwebBaseUrl 		= 'http://regwebdev.com';
$regwebBaseUrl 		= 'https://demo3.regweb.no';


// Setup api object
use Regweb\RegwebApi;
use Regweb\Authorization\CredentialsAuthorization;
use Regweb\Authorization\AuthSessionHandler;

$regwebAuth = new CredentialsAuthorization(	$regwebBaseUrl,
											$regwebClientId,
											$regwebClientSecret,
											new AuthSessionHandler());

$regwebApi = new RegwebApi($regwebBaseUrl, $regwebAuth);




/*
 * Testclient specific
 */

$devMode = true;
if ($devMode) {
	ini_set('display_errors', 1);
	error_reporting(E_ALL);
}

function show_error($message, $debug_data = null) {
	global $devMode;
	include('error.php');
	exit();
}