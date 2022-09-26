<?php
	
		//$mailto = "olamidipupofavour@codecube.com.ng";
		$data_array = json_decode($_POST['data']);
		$mailto=$_POST['email'];
		$message = "
		<!DOCTYPE html>
		<head>
		<title>Subscription Notice</title>
		</head>
		<body>
	<b>Hurray 🎉!You finally joined us!</b><br><h2>Now that you've joined us,you'd receive exclusive uodates as they're dished out.</h2><h3>Don't forget to visit our official website <a href='https://codecube.com.ng'>https://codecube.com.ng</a><i>Made with 💓 By Official CodeCube©</i><footer>If you didn't suscribe to our service,kindly ignore this message,<i>".$mailto."</footer></body></html>";
//if it's a ticket,message would be defined.
		$subject = "We're glad to have you here.We've been expecting you.";

		// a random hash will be necessary to send mixed content
		$separator = md5(time());

		// carriage return type (RFC)
		$eol = "\r\n";

		// main header (multipart mandatory)
		$headers = "From: olamidipupofavour@codecube.com.ng" . $eol;
		$headers .= "Reply-To: olamidipupofavour@codecube.com.ng" . $eol;
		$headers .= "Cc: $mailto" . $eol;
		$headers .= "List-Unsubscribe: <mailto:olamidipupofavours@codecube.com.ng?subject=unsubscribe>";
		$headers .= "MIME-Version: 1.0" . $eol;
		$headers .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"" . $eol;
		$headers .= "Content-Transfer-Encoding: 7bit" . $eol;
		$headers .= "This is a MIME encoded message." . $eol;

		// message
		$body = "--" . $separator . $eol;
		$body .= "Content-Type: text/html; charset=UTF-8" . $eol;
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
	