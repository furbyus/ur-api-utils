<?php
include '../vendor/autoload.php';
use UrApi\Utils\Response;

/*
 * Variable declarations
 *
 */

$content = ['data' => ['original', 'original2' => [1, 2, 3]]];
$config = ['pn', 'pv', 'in', 'iv']; //This variable is necessari if you install the package in a no Laravel application
$overwrite = true;

/*
 *   OOPhp example instantiation:
 */

//$response = new Response($content, $config);
//$reponse->append(['data' => ['overwrited']], true);
//$response->header('Test', 'value');

/*
 *   Function helper example instantiation:
 */

$response = uresponse($content, 301, ['Test' => 'value'], $config)
    ->append(['data' => ['overwrited']], true)
    ->header('Test', 'value');

/*
*   Additional examples, uncomment to test it!
*/

//Test append some other vars
//$response->append(['values' => [1, 2, 3, 4]]);
//Test rewrite the vars
//$response->append(['values' => [4, 3, 2, 1, 1]], $overwrite);

//Dump the object
dump($response);

