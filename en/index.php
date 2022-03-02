<?php

include("../common/generate.php");

function getWordType($string)
{
	$type = NULL;
	$number = NULL;
	$tense = NULL;
	// test ending of word
	mb_internal_encoding("UTF-8");
	if (mb_substr($string, -6) == "nesses") {
		$type = 'noun';
		$number = 'p';
	} elseif (in_array(mb_substr($string, -5), array("ances", "ences", "ships", "ments"))) {
		$type = 'noun';
		$number = 'p';
	} elseif (in_array(mb_substr($string, -4), array("ance", "ence", "ness", "ship", "ment"))) {
		$type = 'noun';
		$number = 's';
	} elseif (mb_substr($string, -4) == "ities") {
		$type = 'noun';
		$number = 'p';
	} elseif (in_array(mb_substr($string, -4), array("able", "ible", "less"))) {
		$type = 'adjective';
	} elseif (mb_substr($string, -4) == "ions") {
		$type = 'noun';
		$number = 'p';
	} elseif (in_array(mb_substr($string, -3), array("ity", "ion"))) {
		$type = 'noun';
		$number = 's';
	} elseif (in_array(mb_substr($string, -3), array("ers", "ars", "ors"))) {
		$type = 'noun';
		$number = 'p';
	} elseif (in_array(mb_substr($string, -3), array("our", "ish", "ful", "ant", "ent", "ive", "ous"))) {
		$type = 'adjective';
	} elseif (in_array(mb_substr($string, -3), array('ate', "ify", "ise", "ize"))) {
		$type = 'verb';
		$tense = 'infinitive';
	} elseif (mb_substr($string, -3) == 'ing') {
		$type = 'verb';
		$tense = 'gerund';
	} elseif (in_array(mb_substr($string, -2), array("er", "ar", "or"))) {
		$type = 'noun';
		$number = 's';
	} elseif (in_array(mb_substr($string, -2), array("ic", "al"))) {
		$type = 'adjective';
	} elseif (mb_substr($string, -2) == "ly") {
		$type = 'adverb';
	} elseif (mb_substr($string, -2) == 'en') {
		$type = 'verb';
		$tense = 'infinitive';
	} elseif (mb_substr($string, -2) == 'ed') {
		$type = 'verb';
		$tense = 'past-participle';
	} elseif (mb_substr($string, -1) == 'y') {
		$type = 'adjective';
	} elseif (mb_substr($string, -1) == 's') {
		$type = 'noun';
		$number = 'p';
	} else {
		$type = 'noun';
		$number = 's';
	}
	return [$type, $number, $tense];
}

function storeWordInDB($string, $type, $number, $tense)
{
	if (!include '../common/connect.php') {
		throw new ErrorException("DB connection script not found");
	}

	$db = databaseConnect();

	$stmt = $db->prepare("INSERT INTO generated_words_EN (word, type, number, tense,ip) VALUES (:word, :type, :number, :tense, :ip)");
	$stmt->bindParam(':word', $string);
	$stmt->bindParam(':type', $type);
	$stmt->bindParam(':number', $number);
	$stmt->bindParam(':tense', $tense);
	$stmt->bindParam(':ip', $_SERVER["REMOTE_ADDR"]);
	// insert a row
	$stmt->execute();

}

// Get request parameters and method
$method = $_SERVER['REQUEST_METHOD'];
if ($method == 'GET') {
	$json_filename = dirname(__FILE__) . '/data/proba_table_2char_EN.json';
	$string = generateWord($json_filename);
	// Get the characteristics of the generated word
	[$type, $number, $tense] = getWordType($string);
	// Try to save the word in DB
	try {
		storeWordInDB($string, $type, $number, $tense);
	} catch (Exception $e) {
		// echo "Couldn't save in DB: ",  $e->getMessage(), "\n";
	}
	$response = array('string' => $string, 'type' => $type, 'number' => $number, 'tense' => $tense);
} elseif ($method == 'POST') {
	# TODO retrieve the word from the DB
} else {
	$response = 'Method not allowed';
}

// Output as JSON page
header('Content-Type: application/json; charset=utf-8');
echo json_encode($response, JSON_UNESCAPED_UNICODE);
