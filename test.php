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
$values = array();

if(isset($_POST["result"])) {

	$postvalue = unserialize(base64_decode($_POST['result'])); 
    
    foreach($postvalue as $value) {
    	array_push($values, $value);
    }

}

if(isset($_POST["function"])) {

	if($_POST["function"] == "addValue") {
		array_push($values, $_POST["name"]);
	}
	
}

?>

<!DOCTYPE html>
<html>
	<head>
		<title>Training</title>
		<link rel="stylesheet" type="text/css" href="css/style.css">
		<link rel="icon" href="images/favicon.jpg">
		<style>
			.onclick-menu {
			    display: inline-block;
			    padding: 0.2em 0.5em;
			    border-radius: 5px;
			    background-color: gray;
			}
			.onclick-menu:focus .onclick-menu-content {
			    display: block;
			}
			.onclick-menu-content {
			    position: absolute;
			    z-index: 999;
			    top: 20%;
			    bottom: 20%;
			    left: 20%;
			    right: 20%;
			    display: none;
			    background-color: rgba(0,0,0,.5);
			    padding: 1em;
			}

			.onclick-menu-content:hover {
				display: block;
			}
		</style>
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

		<!-- parcour -->
		<div class="parcour">
			<form method="POST">
				<input type="hidden" name="function" value="addTrack">
				<h2>Parcour toevoegen</h2>
				<p>Naam: <input type="text" name="name" autocomplete="off">.</p>
				<?php

				foreach($values as $i => $value) {
					echo '<p>' . $value . ': <input type="text" name="values[' . $i . ']">.</p>';
				}

				?>
				<div tabindex="0" class="onclick-menu">
					add value
				    <div class="onclick-menu-content">
				    	<form method="POST">
				    		<input type="hidden" name="function" value="addValue">
				        	<h2 style="color: white">Value toevoegen</h2>
				        	<p>Value naam: <input type="text" name="name" autocomplete="off"></p>
				        	<input type="submit" value="Toevoegen">
				        	<?php $postvalue = base64_encode(serialize($values)); ?>
							<input type="hidden" name="result" value="<?php echo $postvalue; ?>">
				        </form>
				    </div>
				</div>
				
				<br>
				<input type="submit" value="Parcour toevoegen">
				<a href="bikesetup.php">Terug</a>
			</form>
		</div>
	</body>
</html>