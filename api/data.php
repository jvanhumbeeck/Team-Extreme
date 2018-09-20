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

	public function getMessage($location)
	{
		$node = $location;
		if(strpos($location, "/") !== false) {
			$node = $this->xml->xpath($location)[0];
		}

		$string = $node['string'];
		if(strpos($string, 'Y') !== false) {
			$children = $node->children();
			$string = str_replace("X", "<span>" . $children[0] . "</span>", $string);
			$string = str_replace("Y", "<span>" . $children[1] . "</span>", $string);
			return $string;
		}else {
			$string = str_replace("X", "<span>" . $node . "</span>", $string);
			return $string;
		}
		return $string;
	}

	public function setMessage($location, $parentDir = true, $disabled = false)
	{
		$node = $location;
		if(strpos($location, "/") !== false) {
			$node = $this->xml->xpath($location)[0];
		}
		$parent = $node->xpath("..")[0];
		$string = $node['string'];
		if(strpos($string, 'Y') !== false) {
			$children = $node->children();
			$string = str_replace("X", "<input type=\"text\" name=\"" . (($parentDir) ? $parent->getName() . "_" : "") . $children[0]->getName() . "\" value=\"" . $children[0] . "\" " . (($disabled) ? "readonly" : "") . " autocomplete=\"off\">", $string);
			$string = str_replace("Y", "<input type=\"text\" name=\"" . (($parentDir) ? $parent->getName() . "_" : "") . $children[1]->getName() . "\" value=\"" . $children[1] . "\" " . (($disabled) ? "readonly" : "") . " autocomplete=\"off\">", $string);
			return $string;
		}else {
			$string = str_replace("X", "<input type=\"text\" name=\"" . (($parentDir) ? $parent->getName() . "_" : "") . $node->getName() . "\" value=\"" . $node . "\" " . (($disabled) ? "readonly" : "") . " autocomplete=\"off\">", $string);
			return $string;
		}
		return $string;
	}

	public function setNode($location, $value)
	{
		$this->xml->xpath($location)[0][0] = $value;
	}

	public function getNode($location)
	{
		return $this->xml->xpath($location)[0][0];
	}

	public function removeNode($location, $path = true, $node = "")
	{
		if($path) {
			unset($this->xml->xpath($location)[0][0]);
		}else {
			unset($location->xpath($node)[0][0]);
		}
	}

	public function addNode($location, $nodeName, $string = null, $path = true, $value = "")
	{
		if($path) {
			$loc = $this->xml->xpath($location)[0]->addChild(strtolower($nodeName), $value);
			if($string != null) {
				$loc->addAttribute("string", $string);
			}
		}else {
			$loc = $location->addChild(strtolower($nodeName), $value);
			if($string != null) {
				$loc->addAttribute("string", $string);
			}
		}
		
	}

	public function existsNode($location, $path = true, $node = "")
	{
		if($path) {
			if(isset($this->xml->xpath(strtolower($location))[0])) {
				return true;
			}else {
				return false;
			}
		}else {
			if(isset($location->xpath(strtolower($node))[0])) {
				return true;
			}else {
				return false;
			}
		}
	}

	public function getExamlpeTrack() {
		return $this->xml->tracks->example;
	}

	public function getTracks()
	{
		return $this->xml->tracks->track;
	}

	public function getTrack($name)
	{
		foreach ($this->xml->tracks->track as $track) {
			if(strtolower($track->naam) == strtolower($name)) {
				return $track;
			}
		}

		return null;
	}

	public function trackExists($name) {
		foreach ($this->xml->tracks->track as $track) {
			if(strtolower($track->naam) == strtolower($name)) {
				return true;
			}
		}

		return false;
	}

	public function addTrack($name, $array)
	{
		$track = $this->xml->tracks->addChild("track");
		$track->addChild("naam", $name)->addAttribute("string", "Naam: X.");

		foreach($array as $key => $value) {
			$track->addChild($key, $value)->addAttribute("string", $this->getNode("tracks//example//" . $key)['string']);
		}
	}

	public function editTrack($name, $array)
	{
		$track = $this->getTrack($name);

		$new = array();
		foreach($array as $key => $value) {
			if(isset($track->xpath($key)[0])) {
				$track->xpath($key)[0][0] = $value;
			}else {
				$new[$key] = $value;
			}
		}

		foreach($new as $key => $value) {
			$track->addChild($key, $value)->addAttribute("string", $this->getNode("tracks//example//" . $key)['string']);
		}

		echo $this->asXML();
	}

	public function removeTrack($name)
	{
		$count = 0;
		foreach ($this->xml->tracks->track as $track) {
			if($track->naam == $name) {
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
	}

	public function getLogs()
	{
		return $this->xml->logs->log;
	}

	public function printValue($value)
	{
		return '<span>' . $value . '</span>';
	}

	public function asXML() {
		return $this->xml->asXML();
	}

	public function saveXml()
	{

		foreach($this->xml->xpath("//minuten") as $minuten) {
			if($minuten[0] >= 60) {
				$minuten[0] = $minuten[0] - 60;
				$parent = $minuten[0]->xpath("..")[0];
				$parent->uren += 1;
			}
		}

		$dom = new DOMDocument("1.0");
		$dom->preserveWhiteSpace = false;
		$dom->formatOutput = true;
		$dom->loadXML($this->xml->asXML());
		$xml = new SimpleXMLElement($dom->saveXML());
		$xml->saveXML('data/' . $this->name . '.xml');
	}
}

?>