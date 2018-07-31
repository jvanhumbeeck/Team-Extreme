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

	/**
	 * Get total hours driven with the bike
	 * @return int the total hours
	 */
	public function getTotalHours()
	{
		return $this->xml->info->hours;
	}

	/**
	 * Set total hours driven to a new value
	 * @param int $new the new value
	 */
	public function setTotalHours($new)
	{
		$this->xml->info->hours = $new;
	}

	public function getPistonHours()
	{
		return $this->xml->info->piston;
	}

	public function setPistonHours($new)
	{
		$this->xml->info->piston = $new;
	}

	public function getOilHours()
	{
		return $this->xml->info->oil;
	}

	public function setOilHours($new)
	{
		$this->xml->info->oil = $new;
	}

	public function getOilfilterHours()
	{
		return $this->xml->info->oil_filter;
	}

	public function setOilfilterHours($new)
	{
		$this->xml->info->oil_filter = $new;
	}



	public function getAlert()
	{
		return $this->xml->settings->alert;
	}

	public function setAlert($new)
	{
		$this->xml->settings->alert = $new;
	}

	public function getPistonChange()
	{
		return $this->xml->settings->piston;
	}

	public function setPistonChange($new)
	{
		$this->xml->settings->piston = $new;
	}

	public function getOilChange()
	{
		return $this->xml->settings->oil;
	}

	public function setOilChange($new)
	{
		$this->xml->settings->oil = $new;
	}

	public function getOilfilterChange()
	{
		return $this->xml->settings->oil_filter;
	}

	public function setOilFilterChange($new)
	{
		$this->xml->settings->oil_filter = $new;
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

	public function addTrack($name, $adress, $tire, $susp_front, $susp_rear)
	{
		$track = $this->xml->tracks->addChild("track");
		$track->addChild("name", $name);
		$track->addChild("adress", $adress);
		$track->addChild("tire", $tire);
		$track->addChild("susp_front", $susp_front);
		$track->addChild("susp_rear", $susp_rear);
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