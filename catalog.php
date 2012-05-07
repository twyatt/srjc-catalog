<?php
class Catalog {

	private $url;
	
	public $response;
	public $userAgent;
	
	public function __construct($url) {
		$this->url = $url;
	}
	
	public function fetch($post) {
		$curl = curl_init();
		
		curl_setopt($curl, CURLOPT_URL, $this->url);
		curl_setopt($curl, CURLOPT_REFERER, $this->url);
		curl_setopt($curl, CURLOPT_USERAGENT, $this->userAgent);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/x-www-form-urlencoded',
			'Content-Length: ' . strlen($post),
		));
		
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		
		$this->response = curl_exec($curl);
		curl_close($curl);
	}
	
	public function parse() {
		$doc = new DOMDocument();
		$doc->resolveExternals = false;
		$doc->validateOnParse = true;
		@$doc->loadHTML($this->response);
		
		$div = $doc->getElementById('pnlSectionsCourseOutlineData');
		if ($div == null) {
			echo "Data not found.\n";
			//echo $this->response;
			return false;
		}
		
		$xpath = new DOMXPath($doc);
		
		$query = ".//tr[@class='HeadingRow']";
		$rows = $xpath->query($query, $div);
		$row = $rows->item(0);
		if ($row != null) {
			$query = ".//td[@class='HeadingCell']";
			$cells = $xpath->query($query, $row);
			$this->fields = $this->parseData($cells);
		} else {
			echo "Data header not found.\n";
			return false;
		}
		
		$results = array();
		$query = ".//tr[@class='DataRow']";
		$rows = $xpath->query($query, $div);
		
		foreach ($rows as $row) {
			$query = ".//td[@class='DataCell']";
			$cells = $xpath->query($query, $row);
			
			if ($cells->length == count($this->fields)) {
				$data = $this->parseData($cells);
				
				$result = array_combine($this->fields, $data);
				$results[] = $result;
			}
		}
		
		return $results;
	}
	
	private function parseData(DOMNodeList $cells) {
		$data = array();
		$i = 0;
		foreach ($cells as $cell) {
			$data[$i++] = $cell->nodeValue;
		}
		return $data;
	}
	
}