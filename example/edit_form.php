<?php
require_once 'include.php';

$user = $regwebApi->getUser();
$member = $user->member;

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

<form action="edit_submit.php" method="post">
	<div class="form-group">
		<label>Fornavn</label>
		<input 	type="text"
				name="firstname"
				value="<?php print htmlentities($member->firstname)?>"
				class="form-control">
	</div>
	<div class="form-group">
		<label>Etternavn</label>
		<input 	type="text"
				name="lastname"
				value="<?php print htmlentities($member->lastname)?>"
				class="form-control">
	</div>
	<div class="form-group">
		<label>Adresse1</label>
		<input 	type="text"
				name="address1"
				value="<?php print htmlentities($member->address1)?>"
				class="form-control">
	</div>
	<div class="form-group">
		<label>Adresse2</label>
		<input 	type="text"
				name="address2"
				value="<?php print htmlentities($member->address2)?>"
				class="form-control">
	</div>
	<div class="form-group">
		<label>Postnummer</label>
		<input 	type="text"
				name="postalcode"
				value="<?php print htmlentities($member->postalcode)?>"
				class="form-control">
	</div>
	<div class="form-group">
		<label>Telefon1</label>
		<input 	type="text"
				name="phone1"
				value="<?php print htmlentities($member->phone1)?>"
				class="form-control">
	</div>
	<div class="form-group">
		<label>Telefon2</label>
		<input 	type="text"
				name="phone2"
				value="<?php print htmlentities($member->phone2)?>"
				class="form-control">
	</div>
	<div class="form-group">
		<label>Mobil</label>
		<input 	type="text"
				name="mobile"
				value="<?php print htmlentities($member->mobile)?>"
				class="form-control">
	</div>
	<div class="form-group">
		<label>Epost</label>
		<input 	type="text"
				name="email"
				value="<?php print htmlentities($member->email)?>"
				class="form-control">
	</div>
	
	<input 	type="hidden"
			name="id"
			value="<?php print htmlentities($member->id)?>">
			
	<input 	type="submit"
			value="Lagre"
			class="btn btn-primary">
</form>

</body>
</html>