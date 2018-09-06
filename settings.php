<?php

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
		<title>Instellingen</title>
		<link rel="stylesheet" type="text/css" href="css/style.css">
		<link rel="stylesheet" type="text/css" href="css/page.css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
		<link rel="icon" href="images/favicon.png">
	</head>
	
	<body class="tech" style="background-color: #802a2a;">
		<!-- header -->
		<div class="header">
			<h1 onclick="window.location.href = 'index.html';">Rememxbr</h1>
			<div class="nav">
				<a href="index.html"><span text="home">home</span></a>
				<a href="bikesetup.php"><span text="bikesetup">bikesetup</span></a>
			</div>
		</div>

		<!-- settings -->
		<div class="settings">
			<form method="post" action="data.php">
				<input type="hidden" name="function" value="settings">
				<h2>Instellingen</h2>
				<?php
				foreach($data->getSettings()->children() as $node) {
					if($node->getName() == "alert") {
						echo "<p>" . $data->setMessage($node) . "</p>";
					}else {
						echo "<p>" . $data->setMessage($node) . ' <a class="remove" href="' . $node->getName() . '" onclick="return removeVariable(this);"><span class="delete"></span>Remove</a></p>';
					}
				}
				foreach($data->getInfo()->children() as $node) {
					if($node->getName() == "totaal") {
						echo "<p>" . $data->setMessage($node) . "</p>";
					}else {
						echo "<p>" . $data->setMessage($node) . ' <a class="remove" href="' . $node->getName() . '" onclick="return removeVariable(this);"><span class="delete"></span>Remove</a></p>';
					}
				}
				?>
				<button class="button" onclick="return openVariable();">Item toevoegen</button><br>
				<input type="submit" value="Instellingen opslaan">
				<a class="terug" href="bikesetup.php"><span class="back"></span>Terug</a>
			</form>
		</div>

		<div class="addVariable">
			<div class="container">
				<div class="holder">
					<h1>Item toevoegen</h1>
					<p>Naam: <input id="name" type="text" autocomplete="off"> <span id="name_err" class="error"></span><br>(Zonder spaties)<br>(Bv: "Onderhoud").</p>
					<p>Weergave: <input id="string" type="text" autocomplete="off"> <span id="string_err" class="error"></span><br>(Met X als uren en Y als minuten.)<br>(Bv: "Onderhoud: X uur en Y minuten geleden.").</p>
					<p>Set: <input id="set" type="text" autocomplete="off"> <span id="set_err" class="error"></span><br>
						(Met X als uren) <br>(Bv: "Onderhoud om de X uur.").</p>
					<input type="submit" onclick="return addVariable();" value="Item toevoegen">
					<button class="terug" onclick="return back();"><span class="back"></span>Terug</button>
				</div>
			</div>
		</div>

		<script>

			function openVariable() {
				document.getElementsByClassName("addVariable")[0].style.display = "block";
				return false;
			}

			function addVariable() {

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
				if(document.getElementById("string").value.indexOf('X') < 0 || document.getElementById("string").value.indexOf('Y') < 0) {
					document.getElementById("string_err").innerHTML = "Geen X en Y.";
					return;
				}

				document.getElementById("string_err").innerHTML = "";

				//check set input
				if(document.getElementById("set").value.indexOf('X') < 0) {
					document.getElementById("set_err").innerHTML = "Geen X.";
					return;
				}

				document.getElementById("set_err").innerHTML = "";

				var array = [["name", document.getElementById("name").value], ["string", document.getElementById("string").value], ["set", document.getElementById("set").value]];

				sendData("addSetting", array);

				return false;
			}

			function removeVariable(link) {
				var href = link.getAttribute("href");
				var array = [["node", href]];

				//remove variable
				sendData("removeSetting", array);
				
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