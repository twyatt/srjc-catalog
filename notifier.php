<?php
class Notifier {
	
	public $to;
	
	public function __construct($to) {
		$this->to = $to;
	}
	
	public function notify($name, $data) {
		$message = $this->getMessage($name, $data);
		mail($this->to, $name, $message);
	}
	
	public function getMessage($name, $data) {
		return "$name (section " . $data['Sect'] . ") is " . $data['Status'] . " with " . $data['Seats'] . " seats available.";
	}
	
}