<?php
class Fetcher {
	
	public $delay = 5; // seconds
	public $retries = 5;
	public $data = array();
	
	private $catalog;
	private $retry = 0;
	
	public function __construct(Catalog $catalog) {
		$this->catalog = $catalog;
	}
	
	public function run($name, $post) {
		$this->retry = 0;
		$this->fetch($name, $post);
	}
	
	private function fetch($name, $post) {
		echo "Fetching data for $name\n";
		$this->catalog->fetch($post);
		
		try {
			$result = $this->catalog->parse();
		} catch (DataNotFoundException $e) {
			sleep($this->delay);
			$this->retry++;
			if ($this->retry > $this->retries) {
				echo "Max retries reached. Giving up.\n";
				$this->data[$name] = false;
				return;
			}
			echo "Retry " . $this->retry . " of " . $this->retries . "...\n";
			$this->fetch($name, $post);
			return;
		}
		
		//echo "RESULT:\n";
		//print_r($result);
		$this->data[$name] = $result;
		sleep($this->delay);
	}
	
}