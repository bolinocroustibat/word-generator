<?php

include("../common/generate.php");

function getWordType($string)
{
	$type = NULL;
	$gender = NULL;
	$number = NULL;
	$tense = NULL;
	$conjug = NULL;
	// test ending of word
	mb_internal_encoding("UTF-8");
	if (mb_substr($string, -1) == 'é') {
		$type = 'past-participle';
		$gender = 'm';
		$number = 's';
	} elseif (mb_substr($string, -2) == 'ée') {
		$type = 'past-participle';
		$gender = 'f';
		$number = 's';
	} elseif (mb_substr($string, -2) == 'és') {
		$type = 'past-participle';
		$gender = 'm';
		$number = 'p';
	} elseif (mb_substr($string, -3) == 'ées') {
		$type = 'past-participle';
		$gender = 'f';
		$number = 'p';
	} elseif ((substr($string, -2) == 'er') or (substr($string, -2) == 'ir')) {
		$type = 'verb';
		$tense = 'infinitive';
	} elseif (substr($string, -3) == 'ons') {
		$type = 'verb';
		$tense = 'present';
		$conjug = '4';
	} elseif (substr($string, -2) == 'ez') {
		$type = 'verb';
		$tense = 'present';
		$conjug = '5';
	} elseif ((substr($string, -3)) == 'ent') {
		$type = 'verb';
		$tense = 'present';
		$conjug = '6';
	} elseif (substr($string, -3) == 'ais') {
		$type = 'verb';
		$tense = 'present';
		$conjug = '1';
	} elseif (substr($string, -2) == 'as') {
		$type = 'verb';
		$tense = 'present';
		$conjug = '2';
	} elseif (substr($string, -3) == 'ait') {
		$type = 'verb';
		$tense = 'present';
		$conjug = '3';
	} elseif (substr($string, -5) == 'aient') {
		$type = 'verb';
		$tense = 'past';
		$conjug = '6';
	} elseif (substr($string, -2) == 'ra') {
		$type = 'verb';
		$tense = 'future';
		$conjug = '1';
	} elseif (substr($string, -3) == 'ras') {
		$type = 'verb';
		$tense = 'future';
		$conjug = '2';
	} elseif (substr($string, -3) == 'ont') {
		$type = 'verb';
		$tense = 'future';
		$conjug = '6';
	} elseif (substr($string, -2) == 'if') {
		$type = 'adjective';
		$gender = 'm';
		$number = 's';
	} elseif (substr($string, -3) == 'ive') {
		$type = 'adjective';
		$gender = 'f';
		$number = 's';
	} elseif (substr($string, -3) == 'eux') {
		$type = 'adjective';
		$gender = 'm';
		$number = 'p';
	} elseif (substr($string, -4) == 'euse') {
		$type = 'adjective';
		$gender = 'f';
		$number = 'p';
	} elseif (substr($string, -4) == 'ique') {
		$type = 'adjective';
		$gender = 'm';
		$number = 's';
	} elseif (substr($string, -2) == 'es') {
		$type = 'noun';
		$gender = 'f';
		$number = 'p';
	} elseif (substr($string, -1) == 'e') {
		$type = 'noun';
		$gender = 'f';
		$number = 's';
	} elseif (substr($string, -1) == 's') {
		$type = 'noun';
		$gender = 'm';
		$number = 'p';
	} else {
		$type = 'noun';
		$gender = 'm';
		$number = 's';
	}
	return [$type, $gender, $number, $tense, $conjug];
}

function storeWordInDB($string, $type, $gender, $number, $tense, $conjug)
{
	if (!include '../common/connect.php') {
		throw new ErrorException("DB connection script not found");
	}

	$db = databaseConnect();

	$stmt = $db->prepare("INSERT INTO generated_words_FR (word, type, gender, number, tense, conjug, ip) VALUES (:word, :type, :gender, :number, :tense, :conjug, :ip)");
	$stmt->bindParam(':word', $string);
	$stmt->bindParam(':type', $type);
	$stmt->bindParam(':gender', $gender);
	$stmt->bindParam(':number', $number);
	$stmt->bindParam(':tense', $tense);
	$stmt->bindParam(':conjug', $conjug);
	$stmt->bindParam(':ip', $_SERVER["REMOTE_ADDR"]);
	// insert a row
	$stmt->execute();
}

// Get request parameters and method
$method = $_SERVER['REQUEST_METHOD'];
if ($method == 'GET') {
	$json_filename = dirname(__FILE__) . '/data/proba_table_2char_FR.json';
	$string = generateWord($json_filename);
	// Get the characteristics of the generated word
	[$type, $gender, $number, $tense, $conjug] = getWordType($string);
	// Try to save the word in DB
	try {
		storeWordInDB($string, $type, $gender, $number, $tense, $conjug);
	} catch (Exception $e) {
		// echo "Couldn't save in DB: ",  $e->getMessage(), "\n";
	}
	$response = array('string' => $string, 'type' => $type, 'gender' => $gender, 'number' => $number, 'tense' => $tense, 'conjug' => $conjug);
} elseif ($method == 'POST') {
	# TODO retrieve the word from the DB
} else {
	$response = 'Method not allowed';
}

// Output as JSON page
header('Content-Type: application/json; charset=utf-8');
echo json_encode($response, JSON_UNESCAPED_UNICODE);
