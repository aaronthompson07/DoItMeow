<?php

	function push_publish($room_name, $data)
	{
		global $push_publish;
		global $push_subscribe;
		
		$channel_id = $push_publish;
		$channel_secret = $push_subscribe;
		$auth = base64_encode("$channel_id:$channel_secret");
		$url = "https://api2.scaledrone.com/$channel_id/$room_name/publish";
		
		if (in_array('curl', get_loaded_extensions()))
		{
			$ch = curl_init();
			
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			
			$headers = array(
				"Content-type: application/json",
				"Authorization: Basic $auth"
			);
			
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			
			$server_output = curl_exec ($ch);
			
			curl_close ($ch);
		}
		else if( ini_get('allow_url_fopen') ) 
		{
			$options = array(
				'http' => array(
					'header'  => array(
						"Content-type: application/json",
						"Authorization: Basic $auth",
					),
					'method'  => 'POST',
					'content' => json_encode($data)
				)
			);

			$context  = stream_context_create($options);
			$result = file_get_contents($url, false, $context);
			if ($result === FALSE) 
			{
				// Handle error
			}
		}
	}

?>