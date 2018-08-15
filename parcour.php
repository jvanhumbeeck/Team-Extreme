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

$error = "";

if(isset($_GET["error"])) {
	$error = $_GET["error"];
}

?>

<!DOCTYPE html>
<html>
	<head>
		<title>Parcour</title>
		<link rel="stylesheet" type="text/css" href="css/style.css">
		<link rel="stylesheet" type="text/css" href="css/page.css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
		<link rel="icon" href="images/favicon.png">
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
			<form name="saveTrack" action="data.php" method="POST">
				<input type="hidden" name="function" value="saveTrack">
				<?php

				if(isset($_POST["track"])) {
					echo '<h2>Parcour aanpassen</h2>';
					echo '<input type="hidden" name="track" value=' . $_POST["track"] . '">';
					foreach($data->getTrack($_POST["track"])->children() as $track) {
						if($track->getName() == "naam") {
							echo '<p>' . $data->setMessage($track, false, true) . '</p>';
						}else {
							echo '<p>' . $data->setMessage($track, false) . ' <a class="remove" href="' . $track->getName() . '" onclick="return removeVariable(this, true);"><span class="delete"></span>Remove</a></p>';
						}
					}
					echo '<button class="button" onclick="return openVariable();">Item toevoegen</button><br>';
					echo '<input type="submit" value="Parcour opslaan" onsubmit="return validateForm()">';

				}else {
					echo '<h2>Parcour toevoegen</h2>';
					echo '<p id="error" style="color: red;">' . $error . '</p>';
					foreach($data->getExamlpeTrack()->children() as $track) {
						if($track->getName() == "naam") {
							echo '<p>' . $data->setMessage($track, false) . '</p>';
						}else {
							echo '<p>' . $data->setMessage($track, false) . ' <a class="remove" href="' . $track->getName() . '" onclick="return removeVariable(this, false);"><span class="delete"></span>Remove</a></p>';
						}
					}
					echo '<button class="button" onclick="return openVariable();">Item toevoegen</button><br>';
					echo '<input type="submit" value="Parcour toevoegen" onsubmit="return validateForm()">';
				}


				?>
				
				<a class="terug" href="bikesetup.php"><span class="back"></span>Terug</a>
			</form>

			<div class="addVariable">
				<div class="container">
					<div class="holder">
						<h1>Item toevoegen</h1>
						<p>Naam: <input id="name" type="text" autocomplete="off"> <span id="name_err" class="error"></span><br>(Zonder spaties).</p>
						<p>Weergave: <input id="string" type="text" autocomplete="off"> <span id="string_err" class="error"></span><br>(Met X als de veriabele. Bv: "Druk: X bar.").</p>
						<input type="submit" onclick="return addVariable(<?php echo ((isset($_POST["track"])) ? "true" : "false"); ?>);" value="Item toevoegen">
						<button class="terug" onclick="return back();"><span class="back"></span>Terug</button>
					</div>
				</div>

			</div>

		</div>

		<script>

			function openVariable() {
				document.getElementsByClassName("addVariable")[0].style.display = "block";
				return false;
			}

			function addVariable(track) {

				//check Name input
				if(document.getElementById("name").value == "") {
					document.getElementById("name_err").innerHTML = "Vul een naam in.";
					return;
				}
				else if(document.getElementById("name").value.indexOf(' ') >= 0) {
					document.getElementById("name_err").innerHTML = "Geen spaties in de naam.";
					return;
				}

				document.getElementById("name_err").innerHTML = "";

				//check String input
				if(document.getElementById("string").value.indexOf('X') < 0) {
					document.getElementById("string_err").innerHTML = "Geen X.";
					return;
				}

				document.getElementById("string_err").innerHTML = "";

				var array = [["name", document.getElementById("name").value], ["string", document.getElementById("string").value]];

				if(track == true) {
					var name = document.forms["saveTrack"].elements["naam"].value;
					var narray = [["name", document.getElementById("name").value], ["string", document.getElementById("string").value], ["track", name]];
					array = narray;
				}

				sendData("addVar", array);

				return false;
			}

			function removeVariable(link, track) {
				var href = link.getAttribute("href");
				var array = [["node", href]];

				if(track == true) {
					var name = document.forms["saveTrack"].elements["naam"].value;
					var narray = [["node", href], ["track", name]];
					array = narray;
				}

				//remove variable
				sendData("removeVar", array);
				
				return false;
			}

			function back() {
				document.getElementsByClassName("addVariable")[0].style.display = "none";
				return true;
			}

			function sendData(func, dataArray) {

				var array = "function=" + func;

				for(var i = 0; i < dataArray.length; i++) {
					array += "&" + dataArray[i][0] + "=" + dataArray[i][1];
				}

				var xhttp = new XMLHttpRequest();
				xhttp.onreadystatechange = function() {
					if (this.readyState == 4 && this.status == 200) {
						if(this.responseText == "error") {
							document.getElementById("name_err").innerHTML = "Naam bestaal al.";
						}else {
							location.reload();
						}
					}
				};
				xhttp.open("POST", "data.php", true);
				xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				xhttp.send(array);

			}

		</script>
	</body>
</html>