<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "api/cookies.php";
include "api/data.php";

if(!hasCookie("data")) {
	header('Location: user.php');
	exit();
}

$data = new xmlParser(getCookie("data"));

?>

<!DOCTYPE html>
<html>
	<head>
		<title>Training</title>
		<link rel="stylesheet" type="text/css" href="css/style.css">
		<link rel="stylesheet" type="text/css" href="css/page.css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
		<link rel="icon" href="images/favicon.png">
	</head>
	
	<body class="race" style="background-color: #d23535;">
		<!-- header -->
		<div class="header">
			<h1 onclick="window.location.href = 'index.html';">Team Extreme</h1>
			<div class="nav">
				<a href="index.html"><span text="Home">Home</span></a>
				<a href="bikesetup.php"><span text="Bikesetup">Bikesetup</span></a>
			</div>
		</div>

		<!-- training hours -->
		<form action="data.php" method="POST" id="addHours">
			<input type="hidden" name="function" value="addHours">
			<h2>Training toevoegen</h2>
			<p>Gereden: <input type="text" name="hours" autocomplete="off"> uur en <input type="text" name="minutes" autocomplete="off"> minuten.</p>
			<p style="display: inline-block;">In: </p>
			<div class="autocomplete">
				<input id="input" type="text" name="name" value="<?php if(isset($_POST["name"])){echo $_POST["name"];} ?>" autocomplete="off">
			</div>
			<br>
			<input type="submit" value="Training toevoegen">
			<a class="terug" href="bikesetup.php"><span class="back"></span>Terug</a>
		</form>

		<!-- scripts -->
		<script src="javascript/script.js"></script>
		<script>var tracks = [
		<?php 
		$tracks = $data->getTracks();
		$string = "";
		foreach ($tracks as $track) {
			$string .= '"' . $track->naam . '",';
		}
		echo rtrim($string,", ");
		?>];</script>

	</body>
</html>