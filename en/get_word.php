<?php

include("../common/connect.php");
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
storeWordInDB($string);
echo $string;
