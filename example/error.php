<!DOCTYPE html>
<html lang="no">
<head>
	<title>Regweb REST eksempel</title>
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">
	<style> body { max-width: 600px; margin: 0 auto; } </style>
</head>
<body>

<h1>Regweb webtjeneste eksempel</h1>

<h2>Feilmelding</h2>

<?php print $message?>

<?php
if ($devMode) {
	echo '<h3>Debug data</h3>';
	foreach ($debug_data as $key => $value) {
		echo '<h4>'.$key.'</h4>';
		echo '<pre>';
		print_r($value);
		echo '</pre>';
	}
}

?>
</body>
</html>