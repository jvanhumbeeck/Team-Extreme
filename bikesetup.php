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
		<title>Team Extreme</title>
		<link rel="stylesheet" type="text/css" href="css/style.css">
		<link rel="icon" href="images/favicon.jpg">
	</head>
	
	<body class="chain" style="background-color: #bb1313;">
		<!-- header -->
		<div class="header">
			<h1 onclick="window.location.href = 'index.html';">Team Extreme</h1>
			<div class="nav">
				<a href="index.html"><span text="Home">Home</span></a>
				<a href="settings.php"><span text="Instellingen">Instellingen</span></a>
			</div>
		</div>

		<!-- body -->
		<div class="body">

			<!-- temp buttons -->
			<form method="POST" action="hours.php">
				<input type="submit" value="Uren Toevoegen">
			</form>

			<form method="POST" action="parcour.php">
				<input type="submit" value="Parcour Toevoegen">
			</form>

			<form method="POST" action="data.php">
				<input type="hidden" name="function" value="changePiston">
				<input type="submit" value="Piston Vervangen">
			</form>

			<form method="POST" action="data.php">
				<input type="hidden" name="function" value="changeOilfilter">
				<input type="submit" value="Olie filter Vervangen">
			</form>

			<form method="POST" action="data.php">
				<input type="hidden" name="function" value="changeOil">
				<input type="submit" value="Olie vervangen">
			</form>

			<!-- Bike Setup -->
			<h1>BikeSetup</h1>

			<!-- Bike info -->
			<div class="info">
				<p>Totaal gereden: <span><?php 
				$total = floatval($data->getTotalHours()); 
				$hours = floor($total);
				$minutes = floatval((floatval($total) - floatval($hours))*floatval(60.0));
				if($minutes < (ceil($minutes) - 0.5)) {
					$minutes = ceil($minutes - 1);
				}
				echo $hours . "</span> uur en <span>" . ceil($minutes) . "</span> minuten";?>.</p>
				<p>Totaal gereden met piston: <span><?php 
				$total = floatval($data->getPistonHours()); 
				$hours = floor($total);
				$minutes = floatval((floatval($total) - floatval($hours))*floatval(60.0));
				if($minutes < (ceil($minutes) - 0.5)) {
					$minutes = ceil($minutes - 1);
				}
				echo $hours . "</span> uur en <span>" . ceil($minutes) . "</span> minuten";?>.</p>
				<p>Totaal gereden met olie filter: <span><?php 
				$total = floatval($data->getOilfilterHours()); 
				$hours = floor($total);
				$minutes = floatval((floatval($total) - floatval($hours))*floatval(60.0));
				if($minutes < (ceil($minutes) - 0.5)) {
					$minutes = ceil($minutes - 1);
				}
				echo $hours . "</span> uur en <span>" . ceil($minutes) . "</span> minuten";?>.</p>
				<p>Totaal gereden met olie: <span><?php 
				$total = floatval($data->getOilHours()); 
				$hours = floor($total);
				$minutes = floatval((floatval($total) - floatval($hours))*floatval(60.0));
				if($minutes < (ceil($minutes) - 0.5)) {
					$minutes = ceil($minutes - 1);
				}
				echo $hours . "</span> uur en <span>" . ceil($minutes) . "</span> minuten";?>.</p>
			</div>

			<!-- Tracks -->
			<div class="parcour-info">
				<form id="track" method="POST">
					<h2>Parcour info</h2>
					<p>Parcour: </p>
					<div class="autocomplete">
						<input id="input" type="text" name="name" value="<?php if(isset($_POST["name"])){echo $_POST["name"];} ?>" autocomplete="off">
					</div>
					<input type="submit" value="Submit">
					<?php

					if(isset($_POST["name"])) {
						$track = $data->getTrack($_POST["name"]);
						if($_POST["name"] == "") {

						}else if($track == null) {
							echo "<p style='display: block; color: #790c0c'>\"". $_POST["name"] . "\" is geen parcour.</p>";
						}else {
							echo "<p style='display: block;'>Adress: <span>" . $track->adress . "</span>.</p>";
							echo "<p style='display: block;'>Band: <span>" . $track->tire . "</span>.</p>";
							echo "<p style='display: block;'>Voorvering: <span>" . $track->susp_front . "</span> klikken.</p>";
							echo "<p style='display: block;'>Achtervering: <span>" . $track->susp_rear . "</span> klikken.</p>";
							echo '</form>';
							echo '<form method="POST" action="data.php">';
							echo '<input type="hidden" name="function" value="bikesetup">';
							echo '<input type="hidden" name="track" value="' . $_POST["name"] . '">';
							echo '<input type="submit" name="editParcour" value="Edit parcour" formaction="parcour.php">';
							echo '<input type="submit" name="deleteParcour" value="Delete parcour">';
							echo '</form>';
						}
					}

					?>
				</form>
			</div>


			<!-- log (shows last training, ...) -->
			<div class="log">
				<h2>Log</h2>
				<?php

				foreach ($data->getLogs() as $log) {
					echo '<p>' . $log->event . '</p>';
				}

				?>
			</div>

			<!-- log out button -->
			<form action="data.php" method="POST">
				<input type="hidden" name="function" value="logout">
				<input type="submit" value="Uitloggen">
			</form>

			<!-- delete account button -->
			<form action="user.php" method="POST">
				<input type="submit" name="signout" value="Account verwijderen">
			</form>

		</div>

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