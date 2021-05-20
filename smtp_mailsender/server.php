<?php
require("./Remote/sources/Server.php");
// require 'PHPMailerAutoload.php';
require_once( "PHPMailer/class.smtp.php" );
require_once( "PHPMailer/class.phpmailer.php" );
$api = new Webix\Remote\Server();

function domain_exists($email, $record = 'MX'){
	list($user, $domain) = explode('@', $email);
	return checkdnsrr($domain, $record);
}

function emailcheck($email){

	$returnvalue;

	if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
		if(domain_exists($email)) {
			$returnvalue=1;
		}
		else {
			$returnvalue=0;
		}
	} 
	else {
		$returnvalue=0;
	}

	return $returnvalue;
}

class DataDao{
	public function trial($trial){
		return html_entity_decode($trial);
	}
	
	public function sendmail($formobject,$contentobject,$tostring){
		//read data from db
		//send data to front end
		
		$obj = json_decode($formobject);
		
		$from = $obj->{'from'};
		$fromname = $obj->{'fromname'};
		
		$subject = $obj->{'subject'};
		
		$content=htmlspecialchars_decode($contentobject);
		$smtphost = $obj->{'smtphost'};
		$smtpport = $obj->{'smtpport'};
		$smtpuser =$obj->{'smtpuser'};
		$smtppass =$obj->{'smtppass'};
		$variable = new stdClass();
		$result=explode("\n",$tostring);   
		error_log($contentobject);          
		
     
		$mail = new PHPMailer;
		
		//$mail->SMTPDebug = 3;                               // Enable verbose debug output
		
		$mail->isSMTP();                                      // Set mailer to use SMTP
		$mail->Host = $smtphost;                              // Specify main and backup SMTP servers
		$mail->SMTPAuth = true;                               // Enable SMTP authentication
		$mail->Username = $smtpuser;                          // SMTP username
		$mail->Password = $smtppass;                          // SMTP password
		$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
		$mail->Port = $smtpport;                              // TCP port to connect to
		
		$mail->setFrom($from, $fromname);
		foreach($result as $value){
			if($value!=""){
		$mail->addAddress($value); }                  // Add a recipient, name is optional
		}
		                              
		$mail->Subject = $subject;
		$mail->Body    = $content;
		//$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
		$mail->isHTML(true); 				 // Set email format to HTML

           error_log( "P1" );

		if(!$mail->send()) {
           error_log( "P2" );
			return $mail->ErrorInfo;
			
		} else {
           error_log( "P3" );
			return "success";
		}
	 
	}

	//function to check validity of smtp host
	public function checkinghost($a){
		if(checkdnsrr($a, 'A')){
			return 1;
		}
		else{
			return 0;
		}
	}

	//function to check validity of from addresses
	public function checkingfrom($a){
			return emailcheck($a);
		
	}

	//function to check validity of to addresses
	public function checkingto($a){
		
		$resultant=explode("\n",$a);  
		$finalvalue=1;

		foreach($resultant as $value){  
			$finalvalue=$finalvalue&&emailcheck($value);
		}

		return $finalvalue;
	}

}

$api->setClass("data", new DataDao());

$api->end();