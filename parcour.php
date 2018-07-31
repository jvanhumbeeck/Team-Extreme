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

$name = "";
$adress = "";
$tire = "";
$susp_front = "";
$susp_rear = "";
$hidden = "";

if(isset($_POST["track"])) {
	$track = $data->getTrack($_POST["track"]);
	$name = $track->name;
	$adress = $track->adress;
	$tire = $track->tire;
	$susp_front = $track->susp_front;
	$susp_rear = $track->susp_rear;
	$hidden = "<input type=\"hidden\" name=\"track\" value=\"" . $_POST["track"] . "\">";
}

?>

<!DOCTYPE html>
<html>
	<head>
		<title>Parcour</title>
		<link rel="stylesheet" type="text/css" href="css/style.css">
		<link rel="icon" href="images/favicon.jpg">
	</head>
	
	<body class="corner" style="background-color: #bb1313;">
		<!-- header -->
		<div class="header">
			<h1 onclick="window.location.href = 'index.html';">Team Extreme</h1>
			<div class="nav">
				<a href="index.html"><span text="home">home</span></a>
				<a href="bikesetup.php"><span text="bikesetup">bikesetup</span></a>
			</div>
		</div>

		<!-- parcour -->
		<div class="parcour">
			<form action="data.php" method="POST">
				<input type="hidden" name="function" value="addTrack">
				<?php echo $hidden; ?>
				<h2>Parcour toevoegen</h2>
				<p>Naam: <input type="text" name="name" value="<?php echo $name; ?>" <?php if($name != null || $name != ""){echo "readonly";} ?> autocomplete="off">.</p>
				<p>Adres: <input type="text" name="adress" value="<?php echo $adress; ?>" autocomplete="off">.</p>
				<p>Bandtype: <input type="text" name="tire" value="<?php echo $tire; ?>" autocomplete="off">.</p>
				<p>Voorvering: <input type="text" name="susp_front" value="<?php echo $susp_front; ?>" autocomplete="off"> klikken.</p>
				<p>Achtervering: <input type="text" name="susp_rear" value="<?php echo $susp_rear; ?>" autocomplete="off"> klikken.</p>
				<input type="submit" value="Parcour toevoegen">
				<a href="bikesetup.php">Terug</a>
			</form>
		</div>

	</body>
</html>