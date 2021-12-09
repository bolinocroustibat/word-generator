<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="UTF-8" />
</head>

<body>

	<?php

	include("../common/build_proba.php");

	$alphabet = array("a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "ñ", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z", "-", "'");
	$dictionnary_filename = "data/dictionary_ES.txt";
	$json_filename = 'data/proba_table_2char_ES.json';

	echo '<pre>';
	print_r(build2CharProbaTable($alphabet, $dictionnary_filename, $json_filename));
	echo '</pre>';

	/*
	// Display proba file
	echo '<pre>';
	print_r(json_decode(file_get_contents($json_filename), true));
	echo '</pre>';
	*/

	?>

</body>

</html>