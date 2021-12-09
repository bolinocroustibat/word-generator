<!DOCTYPE html>
<html lang="fr">

<head>
	<meta charset="UTF-8" />
</head>

<body>

	<?php

	include("../common/build_proba.php");

	$alphabet = array("a", "à", "â", "b", "c", "ç", "d", "e", "é", "è", "ê", "ë", "f", "g", "h", "i", "î", "ï", "j", "k", "l", "m", "n", "o", "ô", "p", "q", "r", "s", "t", "u", "ü", "û", "v", "w", "x", "y", "z", "-", "'");
	$dictionnary_filename = "data/dictionary_FR.txt";
	$json_filename = 'data/proba_table_2char_FR.json';

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