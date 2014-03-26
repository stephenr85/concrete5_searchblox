<?php


class SearchBloxClient {
	
	public $baseUrl;
	public $apiKey;
	public $curl;
	
	public function __construct($baseUrl, $apiKey){
		
		$this->baseUrl = preg_replace('/\/$/', '', $baseUrl);
		$this->apiKey = $apiKey;
		
		// Initialize the cURL resource
		$this->curl = curl_init();

		// Set some global cURL options
		curl_setopt_array($this->curl, array(
			CURLOPT_USERPWD        => $user . ':' . $pass,
			CURLOPT_FOLLOWLOCATION => true, // Follow redirects
			CURLOPT_HTTPHEADER     => array('Content-type: xml' ), // Set the type
			CURLOPT_RETURNTRANSFER => true, // Return response to variable
			CURLOPT_CONNECTTIMEOUT => 4,
			CURLOPT_TIMEOUT => 10,
			CURLOPT_SSL_VERIFYPEER => false // Ease SSL connections
		));
	}
	
	
	//CURL it
	private function call( $method, $path, $params, $data = null ) {

		$this->url = $this->baseUrl . $path;

		if( gettype($params) == 'object' ):
			$this->url .= '?' . $this->formatParams($params);
		endif;

		// Set the full URL for the request
		curl_setopt($this->curl, CURLOPT_URL, $this->url );

		switch( $method ):
			case 'GET':
				curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, 'GET');
				break;
			case 'POST':
				curl_setopt_array($this->curl, array(
					CURLOPT_CUSTOMREQUEST => 'POST',
					CURLOPT_POSTFIELDS    => $data
				));
				break;
			case 'PUT':
				curl_setopt_array($this->curl, array(
					CURLOPT_CUSTOMREQUEST => 'PUT',
					CURLOPT_POSTFIELDS    => $data
				));
				break;
			case 'DELETE':
				curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
				break;
		endswitch;

		// Execute the request
		$this->http = new StdClass;
		$this->http->response = curl_exec($this->curl);
		$this->http->code     = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);
		$this->http->time     = curl_getinfo($this->curl, CURLINFO_TOTAL_TIME);
		$this->http->error    = curl_error($this->curl);
		
		$this->statusCode = NULL;
		$this->statusMessage = NULL;
		
		$body = curl_exec($ch);
		if(curl_errno($ch))	{
			echo 'searchblox_curl_warning';
			curl_close($ch);
			$error_count++;
		}
		else {
			curl_close($ch);
		
			$xml = simplexml_load_string($body);
			global $statuscode;
			$statuscode = (string) $xml->statuscode;
			
			if (intval($statuscode)>=100 AND intval($statuscode)<=701) {
				echo searchblox_xml_message_detail($statuscode); // Checking Status code and Display status messages	
				if ( intval($statuscode) == 100 ) {
					$error_count = 0; // After first succesful connection, lets reset error counter 
				}
				//else $error_count++; // Skip if document cannot be indexed
			}
			
			elseif ( $statuscode <> '' ) {
				echo 'searchblox_unknown_reply';
				//$error_count++; // Skip if document cannot be indexed
			}
		}

		// Handle specific HTTP errors
		switch( $this->http->code ):
			case 0:
				trigger_error('Server returned error 0 ' . $this->http->error );
			case 401:
				trigger_error('Server returned error 401 Unauthorized: ' . $this->url);
				return false;
				break;
			case 403:
				trigger_error('Server returned error 403 Forbidden: ' . $this->url);
				return false;
				break;
			case 404:
				trigger_error('Server returned error 404 Not found: ' . $this->url);
				return false;
				break;
		endswitch;

		// Handle general HTTP errors
		if( $this->http->code > 400 ) {
			trigger_error('Server returned error ' . $this->http->code . ': ' . $this->url);
			return false;
		}

		// Parse any XML server errors
		if( $xml = simplexml_load_string($this->http->response, null, LIBXML_NOERROR) ):
			return $xml;
		else:
			trigger_error('The response from the server was not valid XML');
			return $this->http->response;
		endif;

	}
	
	// Build a valid query string from an object
	public function formatParams( $params ) {
		if( gettype($params) != 'object' ):
			throw new Exception('Params must be an object');
		else:
			$pairs = array();
			foreach( $params as $name => $value ):
				$pair = urlencode($name);
				if( $value != '' ) $pair .= '=' . urlencode($value);
				$pairs[] = $pair;
			endforeach;
			$str =  implode('&', $pairs);
		endif;

		return $str;
	}

	// GET - used for retrieving data from the server
	public function get( $path = null, $params = null ) {
		return $this->call( 'GET', $path, $params );
	}

	// POST - Used for complex operations
	public function post( $path, $params, $data ) {
		return $this->call( 'POST', $path, $params, $data );
	}
	
	
	public function responseCodeMessage($code){
		$codes = array("100","101","200","201","301","400","401","500","501","502","503","601","700","701");
		$details = array("Document Indexed","Error Indexing Document","Document Deleted","Document requested for deletion does not exist",
		"Document Not Found","Collection Cleared","Error Clearing Collection","Invalid Collection Name","Invalid Request",
		"Invalid Document Location","Specified collection is not a CUSTOM collection","Invalid API key",
		"Collection Optimized","Error Optimizing Collection");
		
		$code = str_replace($codes,$details,$code);
		if (trim($code) == '') return 'None';
		else return $code;	
	}	
	
	// http://www.searchblox.com/developers-2/api-2/clear-the-collection
	public function clearCollection($colName){
		$postData ='
		<?xml version="1.0" encoding="utf-8"?>
		<searchblox apikey="'.$this->apiKey.'">
		<document colname="'.$colName.'"/>
		</searchblox>
		';
		return $this->post($this->baseUrl.'/api/rest/clear', NULL, $postData);	
	}
	
	// http://www.searchblox.com/developers-2/api-2/search - see "collections" node
	public function getCollections(){
		$xml = $this->search(array('pagesize'=>1));
		$collections = array();
		foreach($xml->collection as $node){
			$collections[$node->id] = $node->name;	
		}
		return $collections;
	}
	
	// http://www.searchblox.com/developers-2/api-2/search
	public function search($params) {
		return $this->get($this->baseUrl.'/servlet/SearchServlet', $params);
	}
	
	/* Destructor */
	public function __destruct() {
		// Destroy the cURL object
		curl_close($this->curl);
	}
}