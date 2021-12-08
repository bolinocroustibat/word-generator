<?php

function build1CharProbaTable($alphabet, $dictionnary_filename, $json_filename)
	{
		$temp = array_fill_keys($alphabet, 0) + array('last letter' => 0);
		$table = array('first letter' => $temp) + array_fill_keys($alphabet, $temp);
		$dictionnary = fopen($dictionnary_filename, "r"); // file must be encoded in UTF-8
		if ($dictionnary) {
			while (($word = fgets($dictionnary)) !== false) { // process the line read
				$first_letter = strtolower(mb_substr($word, 0, 1, 'UTF-8'));
				$table['first letter'][$first_letter] = $table['first letter'][$first_letter] + 1; // count first letters occurences
				for ($i = 0; $i <= strlen($word) && in_array(strtolower(mb_substr($word, $i, 1, 'UTF-8')), $alphabet); $i++) { // parse the word char by char, only if the lowercase char is in the alphabet, for the length of the word
					$current_char = strtolower(mb_substr($word, $i, 1, 'UTF-8'));
					$next_char = strtolower(mb_substr($word, $i + 1, 1, 'UTF-8'));
					if (!in_array($next_char, $alphabet)) {
						$table[$current_char]['last letter'] = $table[$current_char]['last letter'] + 1; // add +1 in the table for each current_char followed by next_char
						break;
					} else {
						$table[$current_char][$next_char] = $table[$current_char][$next_char] + 1; // add +1 in the table for each current_char followed by next_char
					}
				}
			}
			fclose($dictionnary);
		} else {
			echo "Erreur en ouvrant le fichier du dictionnaire !"; // error opening the file.
		}
		if (file_put_contents($json_filename, json_encode($table))) {
			return $table;
		} else {
			return 'Erreur, le fichier "' . $json_filename . '" n\'a pas été enregistré';
		}
	}


	function build2CharProbaTable($alphabet, $dictionnary_filename, $json_filename)
	{
		//building the table structure
		$temp = array();
		foreach ($alphabet as $letter1) {
			foreach ($alphabet as $letter2) {
				array_push($temp, $letter1 . $letter2);
			}
		}
		$temp2 = array_merge($alphabet, $temp);
		$temp3 = array_fill_keys($alphabet, 0) + array('last letter' => 0);
		$table = array('first letter' => array_fill_keys($alphabet, 0)) + array_fill_keys($temp2, $temp3);
		/* filling up the table */
		$dictionnary = fopen($dictionnary_filename, "r"); // file must be encoded in UTF-8
		if ($dictionnary) {
			while (($word = fgets($dictionnary)) !== false) { // process the line read
				$first_letter = strtolower(mb_substr($word, 0, 1, 'UTF-8'));
				if (in_array($first_letter, $alphabet)) {
					$table['first letter'][$first_letter] = $table['first letter'][$first_letter] + 1; // count which first letters occurences
					$second_letter = strtolower(mb_substr($word, 1, 1, 'UTF-8'));
					if (in_array($second_letter, $alphabet)) {
						$table[$first_letter][$second_letter] = $table[$first_letter][$second_letter] + 1; // probability for the 2nd character
						$third_letter = strtolower(mb_substr($word, 2, 1, 'UTF-8'));
						if (!in_array($third_letter, $alphabet)) { // the word has only 2 characters
							$table[$first_letter . $second_letter]['last letter'] = $table[$first_letter . $second_letter]['last letter'] + 1;
						} else { // the word has 3 chars or more
							for ($i = 0; $i <= strlen($word); $i++) { // parse the word char by char 
								$char1 = strtolower(mb_substr($word, $i, 1, 'UTF-8'));
								$char2 = strtolower(mb_substr($word, $i + 1, 1, 'UTF-8'));
								$char3 = strtolower(mb_substr($word, $i + 2, 1, 'UTF-8'));
								if (in_array($char2, $alphabet) && in_array($char3, $alphabet)) { // if the next two characters are readable
									$table[$char1 . $char2][$char3] = $table[$char1 . $char2][$char3] + 1;
								} elseif (!in_array($char3, $alphabet) && in_array($char2, $alphabet)) {
									$table[$char1 . $char2]['last letter'] = $table[$char1 . $char2]['last letter'] + 1;
									break;
								} elseif (!in_array($char2, $alphabet)) {
									$table[$char1]['last letter'] = $table[$char1]['last letter'] + 1;
									break;
								}
							}
						}
					} else { // word has 1 char only
						$table[$first_letter]['last letter'] = $table[$first_letter]['last letter'] + 1;
					}
				} else { //word is not readable
				}
			}
			fclose($dictionnary);
		} else {
			echo "Erreur en ouvrant le fichier du dictionnaire !"; // error opening the file.
		}
		if (file_put_contents($json_filename, json_encode($table))) {
			return $table;
		} else {
			return 'Erreur, le fichier "' . $json_filename . '" n\'a pas été enregistré';
		}
	}
