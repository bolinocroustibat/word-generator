<?php

include("../connect.php");

function generateWordBy1Char($json_filename) {
	$table = json_decode(file_get_contents($json_filename), true);
	$rand = mt_rand(0,array_sum($table['first letter']));
	$sum = 0;
	foreach ($table['first letter'] as $letter=>$value) {
		$sum += $value;
		if ( $sum >= $rand ) {
			$current_letter = $letter;
			break;
		}
	}
	$word = $current_letter;
	for ($i=1; $i < 100; $i++) {
		$rand = mt_rand(0,array_sum($table[$current_letter]));
		$sum = 0;
		foreach ($table[$current_letter] as $letter=>$value) {
			$sum += $value;
			if ( $sum >= $rand ) {
				if ($letter =='last letter'){
					break 2; // out of the two loops
				} else {
					$current_letter = $letter;
					break;
				}
			}
		}
		$word = $word.$current_letter;
	}
	return $word;
}

function generateWordBy2Char($json_filename) {
	$table = json_decode(file_get_contents($json_filename), true);
	/* choice of the 1st letter */
	$rand = mt_rand(0,array_sum($table['first letter']));
	$sum = 0;
	foreach ($table['first letter'] as $letter=>$value) {
		$sum += $value;
		if ( $sum >= $rand ) {
			$char1 = $letter;
			break;
		}
	}
	$word = $char1;
	/* choice of the 2nd letter */
	$rand = mt_rand(0,array_sum($table[$char1]));
	$sum = 0;
	foreach ($table[$char1] as $letter=>$value) {
		$sum += $value;
		if ( $sum >= $rand ) {
			if ($letter =='last letter'){
				return $word;// it's a 1-letter word, out of the loop
			} else { //if it's more than 1-letter
				$char2 = $letter;
				$word = $word.$char2; // add the 2nd letter
				/* loop for others chars  */
				for ($i=0; $i < 25; $i++) {
					/* choice of a letter */
					$rand = mt_rand(0,array_sum($table[$char1.$char2]));
					$sum = 0;
					foreach ($table[$char1.$char2] as $letter=>$value) { // choice of a character
						$sum += $value;
						if ( $sum >= $rand) {
							if ($letter =='last letter'){ // end of the word, no more letter
								return $word;
							} else {
								$word = $word.$letter;
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

function storeWordInDB($string) {
	$type = NULL;
	$genre = NULL;
	$number = NULL;
	$tense = NULL;
	$conjug = NULL;
	// prepare sql and bind parameters
	$db = database_connect();
	$stmt = $db->prepare("INSERT INTO generated_words_FR (word, type, genre, number, tense, conjug, ip) VALUES (:word, :type, :genre, :number, :tense, :conjug, :ip)");
	$stmt->bindParam(':word', $string);
	$stmt->bindParam(':type', $type);
	$stmt->bindParam(':genre', $genre);
	$stmt->bindParam(':number', $number);
	$stmt->bindParam(':tense', $tense);
	$stmt->bindParam(':conjug', $conjug);
	$stmt->bindParam(':ip', $_SERVER["REMOTE_ADDR"]);
	// test ending of word
	mb_internal_encoding("UTF-8");
	if ((mb_substr($string, -1)) == 'é'){ $type = 'past-participle'; $genre = 'm'; $number = 's';}
	elseif ((mb_substr($string, -2)) == 'ée'){ $type = 'past-participle'; $genre = 'f'; $number = 's';}
	elseif ((mb_substr($string, -2)) == 'és'){ $type = 'past-participle'; $genre = 'm'; $number = 'p';}
	elseif ((mb_substr($string, -3)) == 'ées'){ $type = 'past-participle'; $genre = 'f'; $number = 'p';}		
	elseif ((substr($string, -2)) == 'er' || (substr($string, -2)) == 'ir'){ $type = 'verb'; $tense = 'infinitive';}
	elseif ((substr($string, -3)) == 'ons'){ $type = 'verb'; $tense = 'present'; $conjug = '4';}	
	elseif ((substr($string, -2)) == 'ez'){ $type = 'verb'; $tense = 'present'; $conjug = '5';}	
	elseif ((substr($string, -3)) == 'ent'){ $type = 'verb'; $tense = 'present'; $conjug = '6';}	
	elseif ((substr($string, -3)) == 'ais'){ $type = 'verb'; $tense = 'present'; $conjug = '1';}
	elseif ((substr($string, -2)) == 'as'){ $type = 'verb'; $tense = 'present'; $conjug = '2';}
	elseif ((substr($string, -3)) == 'ait'){ $type = 'verb'; $tense = 'present'; $conjug = '3';}
	elseif ((substr($string, -5)) == 'aient'){ $type = 'verb'; $tense = 'past'; $conjug = '6';}
	elseif ((substr($string, -2)) == 'ra'){ $type = 'verb'; $tense = 'future'; $conjug = '1';}
	elseif ((substr($string, -3)) == 'ras'){ $type = 'verb'; $tense = 'future'; $conjug = '2';}
	elseif ((substr($string, -3)) == 'ont'){ $type = 'verb'; $tense = 'future'; $conjug = '6';}
	elseif ((substr($string, -2)) == 'if'){ $type = 'adjective'; $genre = 'm'; $number = 's';}
	elseif ((substr($string, -3)) == 'ive'){ $type = 'adjective'; $genre = 'f'; $number = 's';}
	elseif ((substr($string, -3)) == 'eux'){ $type = 'adjective'; $genre = 'm'; $number = 'p';}
	elseif ((substr($string, -4)) == 'euse'){ $type = 'adjective'; $genre = 'f'; $number = 'p';}
	elseif ((substr($string, -4)) == 'ique'){ $type = 'adjective'; $genre = 'm'; $number = 's';}
	elseif ((substr($string, -2)) == 'es'){ $type = 'noun'; $genre = 'f'; $number = 'p';}
	elseif ((substr($string, -1)) == 'e'){ $type = 'noun'; $genre = 'f'; $number = 's';}
	elseif ((substr($string, -1)) == 's'){ $type = 'noun'; $genre = 'm'; $number = 'p';}
	else { $type = 'noun'; $genre = 'm'; $number = 's';}
	// insert a row
	$stmt->execute();
}
$json_filename = dirname( __FILE__ ).'/data/proba_table_2char_FR.json';
$string = generateWordBy2Char($json_filename);
storeWordInDB ($string);
echo $string;
