<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class xmlParser
{
	/**
	 * Name of file
	 * @var String
	 */
	private $name;

	/**
	 * The file loaded as xml
	 * @var simplexml
	 */
	private $xml;

	/**
	 * Constructor
	 * @param string $name Get it from cookie
	 */
	public function __construct($name)
	{
		$this->name = $name;
		$this->xml = simplexml_load_file("data/".$name.".xml");
	}

	public function getAlert()
	{
		return $this->xml->settings->alert;
	}

	public function setAlert($new)
	{
		$this->xml->settings->alert = $new;
	}

	public function getInfo()
	{
		return $this->xml->info;
	}

	public function getSettings()
	{
		return $this->xml->settings;
	}

	public function getTracks()
	{
		return $this->xml->tracks->track;
	}

	public function getTrack($name)
	{
		foreach ($this->xml->tracks->track as $track) {
			if($track->name == $name) {
				return $track;
			}
		}

		return null;
	}

	public function addTrack($name, $array)
	{
		$track = $this->xml->tracks->addChild("track");
		$track->addChild("naam", $name)->addAttribute("string", "Naam: X.");
		foreach($array as $child) {
			$track->addChild($child[0], $child[1])->addAttribute("string", $child[2]);
		}
	}

	public function removeTrack($name)
	{
		$count = 0;
		foreach ($this->xml->tracks->track as $track) {
			if($track->name == $name) {
				unset($this->xml->tracks->track[$count]);
				break;
			}else {
			}
			$count++;
		}
	}

	public function Log($event)
	{
		$log = $this->xml->logs->addChild("log");
		$log->addChild("event", $event);
		$log->addChild("date", date("d/m/Y"));
		if(sizeof($this->xml->logs->log) > 10) {
			unset($this->xml->logs->log[0]);
		}
	}

	public function getLogs()
	{
		return $this->xml->logs->log;
	}

	public function printValue($value)
	{
		echo '<span>' . $value . '</span>';
	}

	public function saveXml()
	{
		$dom = new DOMDocument("1.0");
		$dom->preserveWhiteSpace = false;
		$dom->formatOutput = true;
		$dom->loadXML($this->xml->asXML());
		$xml = new SimpleXMLElement($dom->saveXML());
		$xml->saveXML('data/' . $this->name . '.xml');
	}
}

?>