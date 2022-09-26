<?php

	if($_POST['id'] === "popup-subscribe-form") {
		$stack = json_decode($_POST['data']);
		$email = "";
		foreach ($stack as $key => $value) {
			if (strtolower($value->name_attr) == "email") {
				$email = $value->value;
			}
			$data[strtolower($value->name_attr)] = $value->value;
		}

		foreach( $_FILES as $file) {
			if ( !move_uploaded_file( $file['tmp_name'], dirname(__FILE__) . '/../tmp/' . $file['name'] ) ) {
				echo "error upload file";
			}
		}

		$apiKey = 'daecd20dc781652e65287e816558c8c2-us9';
	    $listId = '303a748ebf';

	    $memberId = md5(strtolower($email));
	    $dataCenter = substr($apiKey,strpos($apiKey,'-')+1);

	    $url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $listId . '/members/' . $memberId;

	    $json = json_encode( array(
			'email_address' => $email,
	        'status'        => 'subscribed', // "subscribed","unsubscribed","cleaned","pending"
	        'merge_fields'  => $data
	    ) );

	    $ch = curl_init($url);

	    curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $apiKey);
	    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);

	    $result = curl_exec($ch);
	    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	    $err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  header("HTTP/1.1 406 Not Acceptable");
		  echo "cURL Error #:" . $err;
		} else {
		  echo $httpCode;
		}
	}
		$mailto = "uniqueinternet2020@gmail.com";

		$data_array = json_decode($_POST['data']);
		$message = "";
		foreach ($data_array as $key => $value) {
			if (isset($value->name) && $value->name !== "") {
				$message .= $value->name.': '.$value->value.'<br>';
			}
		}

		$subject = "";

		// a random hash will be necessary to send mixed content
		$separator = md5(time());

		// carriage return type (RFC)
		$eol = "\r\n";

		// main header (multipart mandatory)
		$headers = "From: $mailto" . $eol;
		$headers .= "Reply-To: $mailto" . $eol;
		$headers .= "MIME-Version: 1.0" . $eol;
		$headers .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"" . $eol;
		$headers .= "Content-Transfer-Encoding: 7bit" . $eol;
		$headers .= "This is a MIME encoded message." . $eol;

		// message
		$body = "--" . $separator . $eol;
		$body .= "Content-Type: text/html; charset=iso-8859-1" . $eol;
		$body .= "Content-Transfer-Encoding: 8bit" . $eol . $eol;
		$body .= "<div>" . $message . "</div>" . $eol . $eol;

		foreach( $_FILES as $file) {
			if ( !move_uploaded_file( $file['tmp_name'], dirname(__FILE__) . '/../tmp/' . $file['name'] ) ) {
				echo "error upload file: " . $file['name'];
				continue;
			}
			$filename = $file['name'];
			$path = dirname(__FILE__) . '/../tmp';
			$file = $path . "/" . $filename;

			$content = file_get_contents($file);
			$content = chunk_split(base64_encode($content));

			// attachment
			$body .= "--" . $separator . $eol;
			$body .= "Content-Type: application/octet-stream; name=\"" . $filename . "\"" . $eol;
			$body .= "Content-Transfer-Encoding: base64" . $eol;
			$body .= "Content-Disposition: attachment" . $eol . $eol;
			$body .= $content . $eol . $eol;
		}

		$body .= "--" . $separator . "--";

		//SEND Mail
		if (mail($mailto, $subject, $body, $headers)) {
			echo "mail send ... OK"; // or use booleans here
		} else {
			echo "mail send ... ERROR!";
			print_r( error_get_last() );
		}
	
		$mailto = "";

		$data_array = json_decode($_POST['data']);
		$message = "";
		foreach ($data_array as $key => $value) {
			if (isset($value->name) && $value->name !== "") {
				$message .= $value->name.': '.$value->value.'<br>';
			}
		}

		$subject = "";

		// a random hash will be necessary to send mixed content
		$separator = md5(time());

		// carriage return type (RFC)
		$eol = "\r\n";

		// main header (multipart mandatory)
		$headers = "From: $mailto" . $eol;
		$headers .= "Reply-To: $mailto" . $eol;
		$headers .= "MIME-Version: 1.0" . $eol;
		$headers .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"" . $eol;
		$headers .= "Content-Transfer-Encoding: 7bit" . $eol;
		$headers .= "This is a MIME encoded message." . $eol;

		// message
		$body = "--" . $separator . $eol;
		$body .= "Content-Type: text/html; charset=iso-8859-1" . $eol;
		$body .= "Content-Transfer-Encoding: 8bit" . $eol . $eol;
		$body .= "<div>" . $message . "</div>" . $eol . $eol;

		foreach( $_FILES as $file) {
			if ( !move_uploaded_file( $file['tmp_name'], dirname(__FILE__) . '/../tmp/' . $file['name'] ) ) {
				echo "error upload file: " . $file['name'];
				continue;
			}
			$filename = $file['name'];
			$path = dirname(__FILE__) . '/../tmp';
			$file = $path . "/" . $filename;

			$content = file_get_contents($file);
			$content = chunk_split(base64_encode($content));

			// attachment
			$body .= "--" . $separator . $eol;
			$body .= "Content-Type: application/octet-stream; name=\"" . $filename . "\"" . $eol;
			$body .= "Content-Transfer-Encoding: base64" . $eol;
			$body .= "Content-Disposition: attachment" . $eol . $eol;
			$body .= $content . $eol . $eol;
		}

		$body .= "--" . $separator . "--";

		//SEND Mail
		if (mail($mailto, $subject, $body, $headers)) {
			echo "mail send ... OK"; // or use booleans here
		} else {
			echo "mail send ... ERROR!";
			print_r( error_get_last() );
		}
	