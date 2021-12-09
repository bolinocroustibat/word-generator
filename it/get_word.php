<?php

include("../common/generate.php");

$json_filename = dirname(__FILE__) . '/data/proba_table_2char_IT.json';
$string = generateWordBy2Char($json_filename);

// Output the word in JSON page
header('Content-Type: application/json; charset=utf-8');
echo json_encode($string);
