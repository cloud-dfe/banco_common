<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../bootstrap.php';

use CloudDFe\BancoCommon\Files;

$temp = sys_get_temp_dir();
$path = $temp . DIRECTORY_SEPARATOR . 'testes';
$files = new Files($path);
$content = "arquivo de teste";
$filename = 'teste.txt';
$files->put($filename, $content);
$response = is_file($path . DIRECTORY_SEPARATOR . $filename);
