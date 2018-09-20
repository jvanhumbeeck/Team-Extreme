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
		<title>Rememxbr</title>
		<link rel="stylesheet" type="text/css" href="css/style.css">
		<link rel="stylesheet" type="text/css" href="css/bikesetup.css">
		<link rel="icon" href="images/favicon.png">
	</head>
	
	<body class="chain" style="background-color: #bb1313;">
		<!-- header -->
		<div class="header">
			<h1 onclick="window.location.href = 'index.html';">Rememxbr</h1>
			<div class="nav">
				<a href="index.html"><span text="Home">Home</span></a>
				<a href="settings.php"><span text="Instellingen">Instellingen</span></a>
			</div>
		</div>

		<!-- Alerts -->
		<div class="alert-list">
			<form id="list" action="data.php" method="POST">
				<input type="hidden" name="function" value="reset">
			</form>
		</div>

		<!-- GRID LAYOUT -->
		<div class="grid-container">

			<!-- body -->
			<div class="body">

				<!-- temp buttons -->
				<form method="POST" action="hours.php">
					<input type="submit" value="Uren Toevoegen">
				</form>

				<form method="POST" action="parcour.php">
					<input type="submit" value="Parcour Toevoegen">
				</form>

				<br>

				<form id="reset" class="block" method="POST" action="data.php">
					<input type="hidden" name="function" value="reset">
					<p>
						Vervang: 
						<select name="name" form="reset">
							<option value="" style="display:none;" selected> selecteer </option>
							<?php

							foreach($data->getInfo()->children() as $info) {
								if($info->getName() == "totaal") {continue;}
								echo '<option value"' . $info->getName() . '">' . $info->getName() . '</option>';
							}

							?>
						</select>
						<input type="submit" name="" value="Vervang">
					</p>
				</form>

				<!-- Bike Setup -->
				<h1>BikeSetup</h1>

				<!-- Bike info -->
				<div class="info">
					<?php

					foreach($data->getInfo()->children() as $child) {
						echo "<p>" . $data->getMessage("info//" . $child->getName()) . "</p>";
					}

					?>
				</div>

				<!-- Tracks -->
				<div class="parcour-info">
					<form class="block" method="POST">
						<h2>Parcour info</h2>
						<p style="margin: 0; display: inline-block;">Parcour: </p>
						<div class="autocomplete">
							<input id="input" type="text" name="name" value="<?php if(isset($_POST["name"])){echo $_POST["name"];} ?>" autocomplete="off">
						</div>
						<input type="submit" value="Submit">
					</form>
						<?php

						if(isset($_POST["name"])) {
							$track = $data->getTrack($_POST["name"]);
							if($_POST["name"] == "") {

							}else if($track == null) {
								echo "<p style='color: #790c0c'>Parcour \"". $_POST["name"] . "\" is niet gevonden.</p>";
							}else {
								foreach($track->children() as $node) {
									echo "<p>" . $data->getMessage($node) . "</p>";
								}
								echo '<form method="POST" action="data.php">';
								echo '<input type="hidden" name="function" value="deleteTrack">';
								echo '<input type="hidden" name="track" value="' . $_POST["name"] . '">';
								echo '<input type="submit" name="editParcour" value="Parcour wijzigen" formaction="parcour.php">';
								echo '<input type="submit" name="deleteParcour" value="Parcour verwijderen">';
								echo '</form>';
							}
						}

						?>
					</form>
				</div>
			</div>


			<!-- log (shows last training, ...) -->
			<div class="log">
				<h2>Log</h2>
				<?php
	
				$log = $data->getLogs();
				for($int = (sizeof($log)>10) ? sizeof($log) - 10 : 0; $int < sizeof($log); $int++) {
					echo '<p>[' . $log[$int]->date . '] ' . $log[$int]->event . '</p>';
				}

				?>
			</div>

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

		<script type="text/javascript">
			function addWarning(name, uur, min) {
				var form = document.getElementById("list");

				var alert = document.createElement("DIV");
				alert.classList.add('warn');
				alert.classList.add('alert');

				var warn = document.createElement("p");
				warn.innerHTML = "Opgelet!";
				warn.classList.add('bold');
				alert.appendChild(warn);

				var string = document.createElement("p");
				string.innerHTML = "Nieuw(e) " + name + " nodig binnen " + uur + " uur en " + min + " minuten.";
				alert.appendChild(string);

				var button = document.createElement("button");
				button.innerHTML = "Vervang";
				button.name = "name";
				button.value = name;
				button.classList.add("button");
				button.addEventListener("submit", function(event) {
					warnClick(event.target);
				});
				alert.appendChild(button);

				form.appendChild(alert);
			}

			function addDanger(name) {
				var form = document.getElementById("list");

				var alert = document.createElement("DIV");
				alert.classList.add('alert');
				alert.classList.add('danger');

				var warn = document.createElement("p");
				warn.innerHTML = "Opgepast!";
				warn.classList.add('bold');
				alert.appendChild(warn);

				var string = document.createElement("p");
				string.innerHTML = "Nieuw(e) " + name + " nodig!";
				alert.appendChild(string);

				var button = document.createElement("button");
				button.innerHTML = "Vervang";
				button.name = "name";
				button.value = name;
				button.classList.add("button");
				button.addEventListener("submit", function(event) {
					warnClick(event.target);
				});
				alert.appendChild(button);

				form.appendChild(alert);
			}

			function warnClick(warn) {
				warn.style.display = "none";
			}
		</script>

		<?php

		//check for alert;

		$alert = $data->getNode("settings//alert");

		foreach($data->getInfo()->children() as $info) {
			if($info->getName() == "totaal") {
				continue;
			}

			$time = $info->uren + ($info->minuten / 60);
			$change = $data->getNode("settings//" . $info->getName());

			if($time >= $change) {
				echo '<script>window.addEventListener("load", addDanger("' . $info->getName() . '"));</script>';
			}else if(($time + $alert) >= $change) {
				if($info->minuten == 0) {
					$uur = $change - $info->uren;
					$min = 0;
				}else {
					$uur = $change - $info->uren - 1;
					$min = 60 - $info->minuten;
				}
				echo '<script>window.addEventListener("load", addWarning("' . $info->getName() . '", ' . $uur . ', ' . $min . '));</script>';
			}
		}

		?>
	</body>
</html>