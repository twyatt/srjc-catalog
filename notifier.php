<?php
class Notifier {
	
	public $to;
	public $from;
	
	public function __construct($to, $from) {
		$this->to = $to;
		$this->from = $from;
	}
	
	public function notify($name, $data) {
		$message = $this->getMessage($name, $data);
		$headers = 'From: ' . $this->from . "\r\n" .
			'X-Mailer: PHP/' . phpversion();
		
		return mail($this->to, $name, $message, $headers);
	}
	
	public function getMessage($name, $data) {
		return "$name (section " . $data['Sect'] . ") is " . $data['Status'] . " with " . $data['Seats'] . " seats available.";
	}
	
}