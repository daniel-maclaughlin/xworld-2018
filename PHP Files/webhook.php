<?php

// Add DB config
include 'dbConfig.php';


//server url as injectected by webhook address 
$jamfurl = $_GET["jamfurl"];


//webhook auth can be used as variables entered into the jamf server
$webhookuser = $_SERVER['PHP_AUTH_USER'];
$webhookpass = $_SERVER['PHP_AUTH_PW'];



//JSON from WebHook
$json = file_get_contents('php://input');
//Make JSON pretty
$obj = json_decode($json, TRUE);

//get device type
$deviceType = $obj["webhook"]["webhookEvent"];

//get serial number, id and name from JSON
$deviceSN   = $obj["event"]["serialNumber"];
$deviceID   = $obj["event"]["jssID"];
$deviceName = $obj["event"]["deviceName"];



//query the Database to find the information
$result = mysqli_query($db,"SELECT * FROM assets WHERE serialnumber = '".$deviceSN."'");

//check to see if the device is there if not then exits out
if ($result->num_rows === 0){ 
        echo 'No results';
    }
else {

//cycle through the rows fields assigning variables
while ($row = mysqli_fetch_array($result)){
$username = $row['username'];
$realname = $row['fullname'];
$email = $row['email'];
$position = $row['position'];
$department = $row['department'];
}



if ( $deviceType =~ "MobileDevice" ) {	
		$xml="<mobile_device>
			 <general>
    				<id>$deviceID</id>
    				<display_name>$deviceName</display_name>
    				<device_name>$deviceName</device_name>
   				<name>$deviceName</name>
    				<serial_number>$deviceSN</serial_number>
  			</general>
		        <location>
                        <username>$username</username>
                        <realname>$realname</realname>
                        <real_name>$realname</real_name>
                        <email_address>$email</email_address>
                        <position>$position</position>
				        <department>$department</department>
                </location>
		</mobile_device>";
		
		//append to jamf url
		$url = $jamfurl . "/JSSResource/mobiledevices/serialnumber/$deviceSN";

	} else {
		$xml="<computer>
    				<general>
    				<id>$deviceID</id>
    				<name>$deviceName</name>
    				<serial_number>$deviceSN</serial_number>
  			</general>
			<location>
				<username>$username</username>
				<realname>$realname</realname>
				<real_name>$realname</real_name>
				<email_address>$email</email_address>
				<position>$position</position>
				<department>$department</department>
			</location>
		</computer>";
		
		//append to jamf url
		$url = $jamfurl . "/JSSResource/computers/serialnumber/$deviceSN";

			
	}



	// Setup and run cURL to call jss api for site assignment
	$ch = curl_init();
  	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY ) ;		// eauthentification method to use  CURLAUTH_BASIC
	curl_setopt($ch, CURLOPT_USERPWD, "$webhookuser:$webhookpass"); // Username and password of the admin JSS accountl
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);					// Return as string
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');					// REST Method 
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);				// For testing only... use a real cert or install CURLOPT_CAINFO file for php. 
	curl_setopt($ch, CURLOPT_VERBOSE, 1); 						// turn verbose on 
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml'));
	curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
	$output = curl_exec($ch);


	// Open the file to get existing content
	$file = "/tmp/curl.log";
	// Write the contents back to the file for logging
	file_put_contents($file, $output);
}

?>

