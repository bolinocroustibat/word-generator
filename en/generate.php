<?php

include("../connect.php");

function generateWordBy1Char($json_filename)
{
	$table = json_decode(file_get_contents($json_filename), true);
	$rand = mt_rand(0, array_sum($table['first letter']));
	$sum = 0;
	foreach ($table['first letter'] as $letter => $value) {
		$sum += $value;
		if ($sum >= $rand) {
			$current_letter = $letter;
			break;
		}
	}
	$word = $current_letter;
	for ($i = 1; $i < 100; $i++) {
		$rand = mt_rand(0, array_sum($table[$current_letter]));
		$sum = 0;
		foreach ($table[$current_letter] as $letter => $value) {
			$sum += $value;
			if ($sum >= $rand) {
				if ($letter == 'last letter') {
					break 2; // out of the two loops
				} else {
					$current_letter = $letter;
					break;
				}
			}
		}
		$word = $word . $current_letter;
	}
	return $word;
}

function generateWordBy2Char($json_filename)
{
	$table = json_decode(file_get_contents($json_filename), true);
	/* choice of the 1st letter */
	$rand = mt_rand(0, array_sum($table['first letter']));
	$sum = 0;
	foreach ($table['first letter'] as $letter => $value) {
		$sum += $value;
		if ($sum >= $rand) {
			$char1 = $letter;
			break;
		}
	}
	$word = $char1;
	/* choice of the 2nd letter */
	$rand = mt_rand(0, array_sum($table[$char1]));
	$sum = 0;
	foreach ($table[$char1] as $letter => $value) {
		$sum += $value;
		if ($sum >= $rand) {
			if ($letter == 'last letter') {
				return $word; // it's a 1-letter word, out of the loop
			} else { //if it's more than 1-letter
				$char2 = $letter;
				$word = $word . $char2; // add the 2nd letter
				/* loop for others chars  */
				for ($i = 0; $i < 25; $i++) {
					/* choice of a letter */
					$rand = mt_rand(0, array_sum($table[$char1 . $char2]));
					$sum = 0;
					foreach ($table[$char1 . $char2] as $letter => $value) { // choice of a character
						$sum += $value;
						if ($sum >= $rand) {
							if ($letter == 'last letter') { // end of the word, no more letter
								return $word;
							} else {
								$word = $word . $letter;
								$char1 = $char2;
								$char2 = $letter;
								break;
							}
						}
					}
				}
			}
		}
	}
}

function storeWordInDB($string)
{
	$db = database_connect();
	// prepare sql and bind parameters
	$db = database_connect();
	$stmt = $db->prepare("INSERT INTO generated_words_EN (word, ip) VALUES (:word, :ip)");
	$stmt->bindParam(':word', $string);
	$stmt->bindParam(':ip', $_SERVER["REMOTE_ADDR"]);
	$stmt->execute(); // insert a row
}

$json_filename = dirname(__FILE__) . '/data/proba_table_2char_EN.json';
$string = generateWordBy2Char($json_filename);
storeWordInDB($string);
echo $string;
