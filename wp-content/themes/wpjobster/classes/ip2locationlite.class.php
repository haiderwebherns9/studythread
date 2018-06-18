<?php
final class ip2location_lite{
	protected $errors = array();
	protected $service = 'api.ipinfodb.com';
	protected $version = 'v3';
	protected $apiKey = '';

	public function __construct(){}

	public function __destruct(){}

	public function setKey($key){
		if(!empty($key)) $this->apiKey = $key;
	}

	public function getError(){
		return implode("\n", $this->errors);
	}

	public function getCountry($host){
		return $this->getResult($host, 'ip-country');
	}

	public function getCity($host){
		return $this->getResult($host, 'ip-city');
	}

	private function getResult($host, $name){
		$ip = @gethostbyname($host);

		if(preg_match('/^(?:25[0-5]|2[0-4]\d|1\d\d|[1-9]\d|\d)(?:[.](?:25[0-5]|2[0-4]\d|1\d\d|[1-9]\d|\d)){3}$/', $ip)){

			if ( ini_get( 'allow_url_fopen' ) ) {
				$xml = @file_get_contents('http://' . $this->service . '/' . $this->version . '/' . $name . '/?key=' . $this->apiKey . '&ip=' . $ip . '&format=xml');

				try{
					$response = @new SimpleXMLElement($xml);

					foreach($response as $field=>$value){
						$result[(string)$field] = (string)$value;
					}

					return $result;
				}
				catch(Exception $e){
					$this->errors[] = $e->getMessage();
					return;
				}
			} else {
				try{
					$headers = array( 'Content-Type: application/json' );
					$fields = array(
						'key' => get_option('wpjobster_ip_key_db'),
						'format' => 'json',
						'ip' => $ip,
					);
					$url = 'http://api.ipinfodb.com/v3/ip-country?' . http_build_query($fields);

					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, $url);
					curl_setopt($ch, CURLOPT_POST, false);
					curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
					$result = curl_exec($ch);
					curl_close($ch);

					$response = json_decode($result, true);
					$result = array();
					foreach($response as $field=>$value){
						$result[(string)$field] = (string)$value;
					}

					return $result;
				}
				catch(Exception $e){
					$this->errors[] = $e->getMessage();
					return;
				}
			}
		}

		$this->errors[] = '"' . $host . '" is not a valid IP address or hostname.';
		return;
	}
}
?>
