<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "api/data.php";
include "api/cookies.php";
include "register.php";

if(hasCookie("data")) {
	header('Location: bikesetup.php');
	exit();
}

?>

<!DOCTYPE html>
<html>
	<head>
		<title>Inloggen</title>
		<link rel="stylesheet" type="text/css" href="css/style.css">
		<link rel="icon" href="images/favicon.jpg">
	</head>
	
	<body class="turnstile" style="background-color: #a02a2a;" onload="this.height = document.documentElement.scrollHeight;">

		<!-- header -->
		<div class="header">
			<h1 onclick="window.location.href = 'index.html';">Team Extreme</h1>
			<div class="nav">
				<a href="index.html"><span text="Home">Home</span></a>
				<a href="bikesetup.php"><span text="BikeSetup">BikeSetup</span></a>
			</div>
		</div>

		<h1>Inloggen</h1>
		<form action="" method="POST">
			<p>Gebruikersnaam: <input type="text" name="username" value="<?php echo $username; ?>"></p>
			<p>Wachtwoord: <input type="password" name="password"></p>
			<p><span style="color: red; text-shadow: 0px 0px 2px #000000;"><?php echo $error; ?></span></p>
			<input type="submit" name="login" value="Login">
		</form>

		<h1>Registreren</h1>
		<form action="" method="POST">
			<p>Gebruikersnaam: <input type="text" name="username2" value="<?php echo $username2; ?>"></p>
			<p>Wachtwoord: <input type="password" name="password2"></p>
			<p>Wachtwoord bevestigen: <input type="password" name="password2_sub"></p>
			<p><span style="color: red; text-shadow: 0px 0px 2px #000000;"><?php echo $error2; ?></span></p>
			<input type="submit" name="register" value="Registreren">
		</form>

	</body>
</html>