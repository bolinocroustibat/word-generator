<?php

include("../common/generate.php");

$json_filename = dirname(__FILE__) . '/data/proba_table_2char_ES.json';
$string = generateWord($json_filename);

// Output as JSON page
header('Content-Type: application/json; charset=utf-8');
$response = array('string' => $string);
echo json_encode($response, JSON_UNESCAPED_UNICODE);
