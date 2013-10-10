<?php
use Regweb\Rest\Exceptions\Unauthorized;
require_once 'include.php';

if ($_POST['username'] == '' || $_POST['password'] == '') {
	show_error('Du må skrive inn brukernavn og passord.');
}

try {
	$regwebAuth->authorizeCredentials($_POST['username'], $_POST['password']);
} catch (Unauthorized $e) {
	show_error('Innlogging var ikke vellykket. Brukernavn eller passord var feil, eller medlemmet er ikke aktivt.');
}

?>
<!DOCTYPE html>
<html lang="no">
<head>
	<title>Regweb REST eksempel</title>
	<meta charset=utf-8> 
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">
	<style> body { max-width: 600px; margin: 0 auto; } </style>
</head>
<body>

<h1>Regweb webtjeneste eksempel</h1>

<p>Du er nå logget inn.</p>

<p>Du kan nå <a href="edit_form.php">endre medlemsdata</a>.</p>

</body>
</html>