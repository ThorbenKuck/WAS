<?php namespace Example; ?>
<?php
/**
 * This file is essential!
 * It MUST exist, else the loader is not able to properly load your package.
 * If you only have one file of functions, copy paste it into here.
 * If you have more than one file, write statements like 
 * 	require "functions.1.php";
 * 	require "functions.2.php";
 * How you name your files is not important, just make sure you could theoreticly use all functions / methods / classes / what ever in this file.
 * 
 * Once you are done, replace all documentations with your own ones!
*/

function example() {
	echo "Insert your own functions here!!!";
}

if(file_exists("functions.1.php")){
	require "functions.1.php";
}

// example();

//This file has been generated!
//Make sure to edit it!!
?>