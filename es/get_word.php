<?php

include("../common/generate.php");

$json_filename = dirname(__FILE__) . '/data/proba_table_2char_ES.json';
$string = generateWord($json_filename);

// Output the word in JSON page
header('Content-Type: application/json; charset=utf-8');
echo json_encode($string, JSON_UNESCAPED_UNICODE);
