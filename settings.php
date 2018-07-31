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
		<link rel="icon" href="images/favicon.jpg">
	</head>
	
	<body class="tech" style="background-color: #802a2a;">
		<!-- header -->
		<div class="header">
			<h1 onclick="window.location.href = 'index.html';">Team Extreme</h1>
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
				<p>Olie filter vervangen om de <input type="text" name="oil_filter" value="<?php echo $data->getOilfilterChange(); ?>" autocomplete="off"> uur.</p>
				<p>Olie vervangen om de <input type="text" name="oil" value="<?php echo $data->getOilChange(); ?>" autocomplete="off"> uur.</p>
				<p>Piston vervangen om de <input type="text" name="piston" value="<?php echo $data->getPistonChange(); ?>" autocomplete="off"> uur.</p>
				<p>Meldingen <input type="text" name="alert" value="<?php echo $data->getAlert(); ?>" autocomplete="off"> uur op voorhand ontvangen.</p>
				<p>Totaal aantal uur gereden: <input type="text" name="total_hours" value="<?php echo $data->getTotalHours(); ?>" autocomplete="off">.</p>
				<p>Uren gereden met piston: <input type="text" name="piston_hours" value="<?php echo $data->getPistonHours(); ?>" autocomplete="off">.</p>
				<p>Uren gereden met olie: <input type="text" name="oil_hours" value="<?php echo $data->getOilHours(); ?>" autocomplete="off">.</p>
				<p>Uren gereden met olie filter: <input type="text" name="oil_filter_hours" value="<?php echo $data->getOilfilterHours(); ?>" autocomplete="off">.</p>
				<input type="submit" value="Instellingen opslaan">
				<a href="bikesetup.php">terug</a>
			</form>

	</body>
</html>