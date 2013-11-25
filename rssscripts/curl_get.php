<?PHP

function curl_data($passed_url, $file){

	echo $passed_url . "<br />";

	$ch = curl_init(); 
	
	$useragent="Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1";

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_USERAGENT, $useragent);

	curl_setopt($ch, CURLOPT_URL, $passed_url); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt($ch, CURLOPT_HEADER, 0); 
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20); 
	curl_setopt($ch, CURLOPT_TIMEOUT, 50); 
	curl_setopt($ch, CURLOPT_MAXREDIRS, 10); 

	$data = curl_exec($ch);

	$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	
	if($http_code == 301 || $http_code == 302){

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($ch, CURLOPT_HEADER, 1); 
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20); 
		curl_setopt($ch, CURLOPT_TIMEOUT, 50); 
		curl_setopt($ch, CURLOPT_MAXREDIRS, 10); 

		$data = curl_exec($ch);

		@list($header, $data) = explode("\n\n", $data, 2);

		$matches = array();

		preg_match('/Location:(.*?)\n/',$header,$matches);

		$new_url = trim(array_pop($matches));

		if(!$new_url){

			echo "error code : " . $http_code . "\n";
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
			curl_setopt($ch, CURLOPT_HEADER, 1); 
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20); 
			curl_setopt($ch, CURLOPT_TIMEOUT, 50); 
			curl_setopt($ch, CURLOPT_MAXREDIRS, 10); 

			$data = curl_exec($ch);

			//list($header, $data) = explode("\n\n", $data, 2);

		}else{

			curl_setopt($ch, CURLOPT_URL, $new_url); 
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
			curl_setopt($ch, CURLOPT_HEADER, 0); 
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20); 
			curl_setopt($ch, CURLOPT_TIMEOUT, 50); 
			curl_setopt($ch, CURLOPT_MAXREDIRS, 10); 

			$data = curl_exec($ch);

		}


	}else{

		if($http_code!=200){

			echo "error code : " . $passed_url . " : " . $http_code . "\n";

		}else{


		}

	}

	curl_close($ch); 
	return $data;

}
