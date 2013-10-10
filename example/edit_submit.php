<?php
require_once 'include.php';

use Regweb\Rest\ResourceType\Member;

$member = new Member();

$member->id 		= $_POST['id'];
$member->firstname 	= $_POST['firstname'];
$member->lastname 	= $_POST['lastname'];
$member->address1 	= $_POST['address1'];
$member->address2 	= $_POST['address2'];
$member->postalcode = $_POST['postalcode'];
$member->phone1 	= $_POST['phone1'];
$member->phone2 	= $_POST['phone2'];
$member->mobile 	= $_POST['mobile'];
$member->email 		= $_POST['email'];

$result = $regwebApi->updateMember($member);

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

<?php if ($result->success):?>
	<p>Oppdatering var vellykket.</p>
	<p><a href="edit_form.php">Tilbake til skjema</a></p>
	<p><a href="logout.php">Logg ut</a></p>
<?php else:?>
	<p>Oppdatering mislykkes, data ikke oppdatert. Se detaljer i responsen.</p>
	<p>Gå tilbake for å endre.</p>
<?php endif;?>

</body>
</html>