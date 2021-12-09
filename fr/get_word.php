<?php

include("../common/generate.php");

function storeWordInDB($string)
{
	if (!include '../common/connect.php') {
		throw new ErrorException("DB connection script not found");
	}

	$db = databaseConnect();

	$type = NULL;
	$genre = NULL;
	$number = NULL;
	$tense = NULL;
	$conjug = NULL;
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
	if ((mb_substr($string, -1)) == 'é') {
		$type = 'past-participle';
		$genre = 'm';
		$number = 's';
	} elseif ((mb_substr($string, -2)) == 'ée') {
		$type = 'past-participle';
		$genre = 'f';
		$number = 's';
	} elseif ((mb_substr($string, -2)) == 'és') {
		$type = 'past-participle';
		$genre = 'm';
		$number = 'p';
	} elseif ((mb_substr($string, -3)) == 'ées') {
		$type = 'past-participle';
		$genre = 'f';
		$number = 'p';
	} elseif ((substr($string, -2)) == 'er' || (substr($string, -2)) == 'ir') {
		$type = 'verb';
		$tense = 'infinitive';
	} elseif ((substr($string, -3)) == 'ons') {
		$type = 'verb';
		$tense = 'present';
		$conjug = '4';
	} elseif ((substr($string, -2)) == 'ez') {
		$type = 'verb';
		$tense = 'present';
		$conjug = '5';
	} elseif ((substr($string, -3)) == 'ent') {
		$type = 'verb';
		$tense = 'present';
		$conjug = '6';
	} elseif ((substr($string, -3)) == 'ais') {
		$type = 'verb';
		$tense = 'present';
		$conjug = '1';
	} elseif ((substr($string, -2)) == 'as') {
		$type = 'verb';
		$tense = 'present';
		$conjug = '2';
	} elseif ((substr($string, -3)) == 'ait') {
		$type = 'verb';
		$tense = 'present';
		$conjug = '3';
	} elseif ((substr($string, -5)) == 'aient') {
		$type = 'verb';
		$tense = 'past';
		$conjug = '6';
	} elseif ((substr($string, -2)) == 'ra') {
		$type = 'verb';
		$tense = 'future';
		$conjug = '1';
	} elseif ((substr($string, -3)) == 'ras') {
		$type = 'verb';
		$tense = 'future';
		$conjug = '2';
	} elseif ((substr($string, -3)) == 'ont') {
		$type = 'verb';
		$tense = 'future';
		$conjug = '6';
	} elseif ((substr($string, -2)) == 'if') {
		$type = 'adjective';
		$genre = 'm';
		$number = 's';
	} elseif ((substr($string, -3)) == 'ive') {
		$type = 'adjective';
		$genre = 'f';
		$number = 's';
	} elseif ((substr($string, -3)) == 'eux') {
		$type = 'adjective';
		$genre = 'm';
		$number = 'p';
	} elseif ((substr($string, -4)) == 'euse') {
		$type = 'adjective';
		$genre = 'f';
		$number = 'p';
	} elseif ((substr($string, -4)) == 'ique') {
		$type = 'adjective';
		$genre = 'm';
		$number = 's';
	} elseif ((substr($string, -2)) == 'es') {
		$type = 'noun';
		$genre = 'f';
		$number = 'p';
	} elseif ((substr($string, -1)) == 'e') {
		$type = 'noun';
		$genre = 'f';
		$number = 's';
	} elseif ((substr($string, -1)) == 's') {
		$type = 'noun';
		$genre = 'm';
		$number = 'p';
	} else {
		$type = 'noun';
		$genre = 'm';
		$number = 's';
	}
	// insert a row
	$stmt->execute();
}

$json_filename = dirname(__FILE__) . '/data/proba_table_2char_FR.json';
$string = generateWord($json_filename);

// Try to save the word in DB
try {
	storeWordInDB($string);
} catch (Exception $e) {
	// echo "Couldn't save in DB: ",  $e->getMessage(), "\n";
}

// Output the word in JSON page
header('Content-Type: application/json; charset=utf-8');
echo json_encode($string, JSON_UNESCAPED_UNICODE);
