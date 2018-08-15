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
	echo "Nothing interesting found.";
}
else if($_POST["function"] == "settings")
{
	foreach ($_POST as $key => $value) {

    	if($key == "function") {continue;}

    	$newKey = str_replace("_", "//", $key);

    	$data->setNode($newKey, $value);

	}

	$data->saveXml();

	header('Location: bikesetup.php');
	exit();
}
else if($_POST["function"] == "saveTrack")
{
	$array = array();

	foreach ($_POST as $key => $value) {
		if($key == "function" ||$key == "naam" || $key == "track") {continue;}
		$array[$key] = $value;
	}

	if(isset($_POST["track"])) {
		$data->editTrack($_POST["naam"], $array);
	}else {
		if($data->trackExists($_POST["naam"])) {
			header('Location: parcour.php?error=Parcour bestaat al.');
			exit();
		}else if($_POST["naam"] == "" || $_POST["naam"] == null) {
			header('Location: parcour.php?error=Gelieve een naam in te vullen.');
			exit();
		}
		$data->addTrack($_POST["naam"], $array);
	}

	$data->saveXml();

	header('Location: bikesetup.php');
	exit();
}
else if($_POST["function"] == "addHours")
{	
	foreach($data->getInfo()->xpath("//uren") as $uren) {
		$uren[0] += (int) $_POST["hours"];
	}

	foreach($data->getInfo()->xpath("//minuten") as $minuten) {
		$minuten[0] += (int) $_POST["minutes"];
	}

	if(empty($_POST["name"])) {
		$log .= ((empty($_POST["hours"])) ? $data->printValue(0) : $data->printValue($_POST["hours"])) . " uur " . ((empty($_POST["minutes"])) ? "" : "en " . $data->printValue($_POST["minutes"]) . " minuten ") . "getraind.";
	}else{
		$log .= ((empty($_POST["hours"])) ? $data->printValue(0) : $data->printValue($_POST["hours"])) . " uur " . ((empty($_POST["minutes"])) ? "" : "en " . $data->printValue($_POST["minutes"]) . " minuten ") . "getraind in <span>" . $data->printValue($_POST["name"]) . "</span>.";
	}

	if((!empty($_POST["hours"])) || (!empty($_POST["minutes"]))) {
		$data->Log($log);
	}
	
	$data->saveXml();

	header('Location: bikesetup.php');
	exit();
}
else if($_POST["function"] == "removeVar") {

	if(isset($_POST["track"])) {
		//remove var from track
		$data->removeNode($data->getTrack($_POST["track"]), false, $_POST["node"]);
	}

	//remove var from example
	$data->removeNode("tracks//example//" . $_POST["node"]);

	$data->saveXml();

}
else if($_POST["function"] == "addVar")
{

	if(isset($_POST["track"])) {
		if($data->existsNode($data->getTrack($_POST["track"]), false, $_POST["name"])) {
			echo "error";
			return;
		}else {
			//add var to track
			$data->addNode($data->getTrack($_POST["track"]), $_POST["name"], $_POST["string"], false);

			if(!$data->existsNode("tracks//example//" . $_POST["name"])) {
				//add var to example
				$data->addNode("tracks//example", $_POST["name"], $_POST["string"]);
			}
			
		}
	}else {
		if(!$data->existsNode("tracks//example//" . $_POST["name"])) {
			//add var to example
			$data->addNode("tracks//example", $_POST["name"], $_POST["string"]);
		}
		
	}

	
	$data->saveXml();
}
else if($_POST["function"] == "addSetting")
{
	//check if setting exists, return "error";
	if($data->existsNode("//info//" . $_POST["name"])) {
		echo "error";
		return;
	}

	//add var to info with its string
	$data->addNode("//info", $_POST["name"], $_POST["string"]);
	$data->addNode("info//" . strtolower($_POST["name"]), "uren", null, true, 0);
	$data->addNode("info//" . strtolower($_POST["name"]), "minuten", null, true, 0);

	//add var to settings with its string
	$data->addNode("//settings", $_POST["name"], $_POST["set"], true, 0);

	$data->saveXml();

}
else if($_POST["function"] == "removeSetting")
{

	//remove var from info
	$data->removeNode("info//" . $_POST["node"]);

	//remove var from settings
	$data->removeNode("settings//" . $_POST["node"]);

	$data->saveXml();
}
else if($_POST["function"] == "reset")
{
	foreach ($data->getNode("info//" . $_POST["name"])->children() as $child) {
		$data->setNode("info//" . $_POST["name"] . "//" . $child->getName(), 0);
	}

	$data->saveXml();

	header('Location: bikesetup.php');
	exit();
}
else if($_POST["function"] == "deleteTrack")
{
	$data->removeTrack($_POST["track"]);
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

?>