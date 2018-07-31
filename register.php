<?php

$error = "";
$username = "";

$error2 = "";
$username2 = "";

if (isset($_POST["login"]))
{
	if (empty($_POST["username"]) || empty($_POST["password"]))
	{
		$error = "Foutieve gebruikersnaam of wachtwoord.";
		$username = $_POST["username"];
	}
	else
	{
		$data = simplexml_load_file("users.xml");
		foreach ($data->user as $user) {
			if($user->name == $_POST["username"] && $user->password == $_POST["password"]) {
				addCookie("data", $_POST["username"]);
				
				header('Location: bikesetup.php');
				exit();
			}
		}

		$username = $_POST["username"];
		$error = "Foutieve gebruikersnaam of wachtwoord.";
	}
}
else if (isset($_POST["register"]))
{
	if (empty($_POST["username2"]) || empty($_POST["password2"]) || empty($_POST["password2_sub"]))
	{
		$error2 = "Gelieve alle velden in te vullen.";
		$username2 = $_POST["username2"];
	}
	else if ($_POST["password2"] != $_POST["password2_sub"])
	{
		$error2 = "Gelieve twee keer het zelfde wachtwoord in te vullen.";
		$username2 = $_POST["username2"];
	}
	else
	{
		$data = simplexml_load_file("users.xml");
		foreach ($data->user as $user) {
			if($user->name == $_POST["username2"]) {
				$error2 = "Gebruikersnaam bestaat al, gelieve een andere te kiezen.";
				$username2 = $_POST["username2"];
				
				return;
			}
		}

		$user = $data->addChild("user");
		$user->addChild("name", $_POST["username2"]);
		$user->addChild("password", $_POST["password2"]);

		$dom = new DOMDocument("1.0");
		$dom->preserveWhiteSpace = false;
		$dom->formatOutput = true;
		$dom->loadXML($data->asXML());
		$xml = new SimpleXMLElement($dom->saveXML());
		$xml->saveXML("users.xml");

		copy('example.xml', 'data/' . $_POST["username2"] . '.xml');

		$file = new xmlParser($_POST["username2"]);
		$file->Log("Account aangemaakt.");
		$file->saveXml();

		addCookie("data", $_POST["username2"]);

		header('Location: bikesetup.php');
		exit();
	}
}
else if (isset($_POST["signout"]))
{
	$file = getCookie("data");

	$path = realpath("data/" . $file . ".xml");

	if(is_writable($path)) {
		unlink($path);
	}else {
		echo "error";
		return;
	}

	$xml = simplexml_load_file("users.xml");

	$count = 0;
	foreach ($xml->user as $user) {
		if($user->name == $file) {
			unset($xml->user[$count]);
			break;
		}
		$count++;
	}

	$dom = new DOMDocument("1.0");
	$dom->preserveWhiteSpace = false;
	$dom->formatOutput = true;
	$dom->loadXML($xml->asXML());
	$xml = new SimpleXMLElement($dom->saveXML());
	$xml->saveXML("users.xml");

	removeCookie("data");

	header('Location: index.html');
	exit();
}

?>