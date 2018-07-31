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
		<link rel="icon" href="images/favicon.jpg">
	</head>
	
	<body class="race" style="background-color: #9e1c1c;">
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
				<input id="input" type="text" name="track" value="<?php if(isset($_POST["track"])){echo $_POST["track"];} ?>" autocomplete="off">
			</div>
			<br>
			<tip>Gebruik een punt als komma.</tip>
			<br>
			<br>
			<input type="submit" value="Training toevoegen">
			<a href="bikesetup.php">Terug</a>
		</form>

		<!-- scripts -->
		<script src="javascript/script.js"></script>
		<script>var tracks = [
		<?php 
		$tracks = $data->getTracks();
		$string = "";
		foreach ($tracks as $track) {
			$string .= '"' . $track->name . '",';
		}
		echo rtrim($string,", ");
		?>];</script>

	</body>
</html>