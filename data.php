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
$log = "";

if(!isset($_POST["function"]))
{
	echo "No function";
}
else if($_POST["function"] == "settings")
{
	if($data->getTotalHours() != $_POST["total_hours"])
	{
		$log .= "Totaal aantal uren: <span>" . $data->getTotalHours() . "</span> &#8594; <span>" . $_POST["total_hours"] . "</span><br>";
		$data->setTotalHours($_POST["total_hours"]);
	}

	if($data->getPistonHours() != $_POST["piston_hours"])
	{
		$log .= "Piston uren: <span>" . $data->getPistonHours() . "</span> &#8594; <span>" . $_POST["piston_hours"] . "</span><br>";
		$data->setPistonHours($_POST["piston_hours"]);
	}

	if($data->getOilHours() != $_POST["oil_hours"])
	{
		$log .= "Olie uren: <span>" . $data->getOilHours() . "</span> &#8594; <span>" . $_POST["oil_hours"] . "</span><br>";
		$data->setOilHours($_POST["oil_hours"]);
	}

	if($data->getOilfilterHours() != $_POST["oil_filter_hours"])
	{
		$log .= "Olie filter uren: <span>" . $data->getOilfilterHours() . "</span> &#8594; <span>" . $_POST["oil_filter_hours"] . "</span><br>";
		$data->setOilfilterHours($_POST["oil_filter_hours"]);
	}

	if($data->getAlert() != $_POST["alert"])
	{
		$log .= "Alert: <span>" . $data->getAlert() . "</span> &#8594; <span>" . $_POST["alert"] . "</span><br>";
		$data->setAlert($_POST["alert"]);
	}

	if($data->getPistonChange() != $_POST["piston"])
	{
		$log .= "Piston vervangen: <span>" . $data->getPistonChange() . "</span> &#8594; <span>" . $_POST["piston"] . "</span><br>";
		$data->setPistonChange($_POST["piston"]);
	}

	if($data->getOilChange() != $_POST["oil"])
	{
		$log .= "Olie vervangen: <span>" . $data->getOilChange() . "</span> &#8594; <span>" . $_POST["oil"] . "</span><br>";
		$data->setOilChange($_POST["oil"]);
	}

	if($data->getOilfilterChange() != $_POST["oil_filter"])
	{
		$log .= "Olie filter vervangen: <span>" . $data->getOilfilterChange() . "</span> &#8594; <span>" . $_POST["oil_filter"] . "</span><br>";
		$data->setOilFilterChange($_POST["oil_filter"]);
	}

	$data->Log($log);

	$data->saveXml();

	header('Location: bikesetup.php');
	exit();
}
else if($_POST["function"] == "getParcour")
{
	$track = $data->getTrack($_POST["name"]);
	echo "name?=" . $_POST["name"] . ";adress?=" . $track->adress . ";tire?=" . $track->tire . ";susp_front?=" . $track->susp_front . ";susp_rear?=" . $track->susp_rear;
}
else if($_POST["function"] == "changePiston")
{
	$log = "Piston vervangen op <span>" . $data->getPistonHours() . "</span> uur.";
	$data->setPistonHours(0);
	$data->Log($log);
	$data->saveXml();

	header('Location: bikesetup.php');
	exit();
}
else if($_POST["function"] == "changeOil")
{
	$log = "Olie vervangen op <span>" . $data->getOilHours() . "</span> uur.";
	$data->setOilHours(0);
	$data->Log($log);
	$data->saveXml();

	header('Location: bikesetup.php');
	exit();
}
else if($_POST["function"] == "changeOilfilter")
{
	$log = "Oliefilter vervangen op <span>" . $data->getOilfilterHours() . "</span> uur.";
	$data->setOilfilterHours(0);
	$data->Log($log);
	$data->saveXml();

	header('Location: bikesetup.php');
	exit();
}
else if($_POST["function"] == "addTrack")
{
	if(isset($_POST["track"])) {
		$data->removeTrack($_POST["track"]);
		$log = "Parcour <span>". $_POST["track"] . "</span> gewijzigd.";
	}else {
		$log = "Parcour <span>" . $_POST["name"] . "</span> toegevoegd.";
	}
	$data->addTrack($_POST["name"], $_POST["adress"], $_POST["tire"], $_POST["susp_front"], $_POST["susp_rear"]);
	$data->Log($log);
	$data->saveXml();

	header('Location: bikesetup.php');
	exit();
}
else if($_POST["function"] == "addHours")
{	
	if(empty($_POST["minutes"])) {
		$data->setTotalHours($data->getTotalHours() + $_POST["hours"]);
		$data->setPistonHours($data->getPistonHours() + $_POST["hours"]);
		$data->setOilHours($data->getOilHours() + $_POST["hours"]);
		$data->setOilfilterHours($data->getOilfilterHours() + $_POST["hours"]);
	}else {
		$data->setTotalHours(floatval($data->getTotalHours()) + floatval($_POST["hours"]) + (floatval($_POST["minutes"])/floatval(60.0)));
		$data->setPistonHours(floatval($data->getPistonHours()) + floatval($_POST["hours"]) + (floatval($_POST["minutes"])/floatval(60.0)));
		$data->setOilHours(floatval($data->getOilHours()) + floatval($_POST["hours"]) + (floatval($_POST["minutes"])/floatval(60.0)));
		$data->setOilfilterHours(floatval($data->getOilfilterHours()) + floatval($_POST["hours"]) + (floatval($_POST["minutes"])/floatval(60.0)));

		$total = floatval($data->getTotalHours());
		if($total < ceil($total) && $total > (ceil($total) - 0.05)) {
			$data->setTotalHours(ceil($total));
		}

		$total = floatval($data->getPistonHours());
		if($total < ceil($total) && $total > (ceil($total) - 0.05)) {
			$data->setPistonHours(ceil($total));
		}

		$total = floatval($data->getOilHours());
		if($total < ceil($total) && $total > (ceil($total) - 0.05)) {
			$data->setOilHours(ceil($total));
		}

		$total = floatval($data->getOilfilterHours());
		if($total < ceil($total) && $total > (ceil($total) - 0.05)) {
			$data->setOilfilterHours(ceil($total));
		}
	}
	if(empty($_POST["track"])) {
		if(empty($_POST["minutes"])) {
			$log = "<span>" . floatval($_POST["hours"]) . "</span> uur getraind.";
		}else {
			$log = "<span>" . floatval($_POST["hours"]) . "</span> uur en <span>" . floatval($_POST["minutes"]) . "</span> minuten getraind.";
		}
	}else {
		if(empty($_POST["minutes"])) {
			$log = "<span>" . floatval($_POST["hours"]) . "</span> uur getraind in <span>" . $_POST["track"] . "</span>.";
		}else {
			$log = "<span>" . floatval($_POST["hours"]) . "</span> uur en <span>" . floatval($_POST["minutes"]) . "</span> minuten getraind in <span>" . $_POST["track"] . "</span>.";
		}
	}
	$data->Log($log);
	$data->saveXml();

	header('Location: bikesetup.php');
	exit();
}
else if($_POST["function"] == "bikesetup")
{
	if(isset($_POST["editParcour"])) {
		
	}else if(isset($_POST["deleteParcour"])) {
		$data->removeTrack($_POST["track"]);
		$log = "Parcour <span>" . $_POST["track"] . "</span> verwijderd.";
	}else {
		header('Location: bikesetup.php');
		exit();
	}

	$data->Log($log);
	$data->saveXml();

	header('Location: bikesetup.php');
	exit();
}
else if($_POST["function"] == "logout")
{
	removeCookie("data");

	header('Location: index.html');
	exit();
}
else if($_POST["function"] == "getTrackHelp")
{
	$help = $_POST["name"];

	foreach($data->getTracks() as $track) {
		if(strtolower($help) == strtolower(substr($track->name, 0, strlen($help)))) {
			echo $track->name . ";";
		}
	}

}

?>