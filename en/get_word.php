<?php

include("../common/generate.php");


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

// Try to save the word in DB
try {
	include("../common/connect.php");
	storeWordInDB($string);
} catch (Exception $e) {
	echo "Couldn't save in DB: ",  $e->getMessage(), "\n";
}

// Output the word in JSON page
header('Content-Type: application/json; charset=utf-8');
echo json_encode($string);
