<?php
include '../vendor/autoload.php';
use UrApi\Utils\Response;


$content = ['data' => ['data1', 'data2' => [1, 2, 3]]];
$info = ['pn', 'pv', 'in', 'iv'];
/*
$response = new Response($content, $info);
$response->body->append(['newData' => [1, 2, 3, 4]]);
$response->header('Test', 'value');
dump($response->send());
*/

$response = response($content, 200, [], $info);
$response->body->append(['newData' => [1, 2, 3, 4]]);
dump($response);