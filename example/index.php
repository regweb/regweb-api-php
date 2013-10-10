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

<form action="login.php" method="post">
	<div class="form-group">
		<label>Brukernavn</label>
		<input type="text" name="username" class="form-control">
	</div>
	<div class="form-group">
		<label>Passord</label>
		<input type="password" name="password" class="form-control">
	</div>
	<input type="submit" value="Logg inn" class="btn btn-primary">
</form>

</body>
</html>