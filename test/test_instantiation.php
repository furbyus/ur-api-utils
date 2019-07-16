<?php
include '../vendor/autoload.php';
use UrApi\Utils\Response;

/*
 * Variable declarations
 *
 */

$content = ['data' => ['data1', 'data2' => [1, 2, 3]]];
$config = ['pn', 'pv', 'in', 'iv'];//This variable is necessari if you install the package in a no Laravel application
$overwrite = true;

/*
 *   OOPhp example instantiation:
 */

//$response = new Response($content, $config);
//$response->header('Test', 'value');

/*
 *   Function helper example instantiation:
 */

$response = uresponse($content, 200, ['Test'=>'value'], $config);


//Test append some vars
$response->body->append($content);
//Test append some other vars
$response->body->append(['values' => [1, 2, 3, 4]]);
//Test rewrite the vars
$response->body->append(['values' => [4, 3, 2, 1, 1]], $overwrite);


 //Dump the object
dump($response);
