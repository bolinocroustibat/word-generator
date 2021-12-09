<!DOCTYPE html>
<html lang="it">

<head>
	<meta charset="UTF-8" />
</head>

<body>

	<?php

	include("../common/build_proba.php");

	$alphabet = array("a", "à", "b", "c", "d", "e", "è", "é", "f", "g", "h", "i", "ì", "í", "î", "j", "k", "l", "m", "n", "ñ", "o", "ò", "ó", "p", "q", "r", "s", "t", "u", "ù", "ú", "v", "w", "x", "y", "z", "-", "'");
	$dictionnary_filename = "data/dictionary_IT.txt";
	$json_filename = 'data/proba_table_2char_IT.json';

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