<?php

include("../common/generate.php");

function storeWordInDB($string)
{
	if (!include '../common/connect.php') {
		throw new ErrorException("DB connection script not found");
	}

	$db = databaseConnect();

	$stmt = $db->prepare("INSERT INTO generated_words_EN (word, ip) VALUES (:word, :ip)");
	$stmt->bindParam(':word', $string);
	$stmt->bindParam(':ip', $_SERVER["REMOTE_ADDR"]);
	$stmt->execute(); // insert a row
}

$json_filename = dirname(__FILE__) . '/data/proba_table_2char_EN.json';
$string = generateWord($json_filename);

// Try to save the word in DB
try {
	storeWordInDB($string);
} catch (Exception $e) {
	// echo "Couldn't save in DB: ",  $e->getMessage(), "\n";
}

// Output the word in JSON page
header('Content-Type: application/json; charset=utf-8');
echo json_encode($string);
