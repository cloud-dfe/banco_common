<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../bootstrap.php';

use CloudDFe\BancoCommon\Strings;

$inputs = [
    'este já está na codificação correta !#%{}[]()?;,.$@&',
    "este n\xe3o est\xe1 na codifica\xe7\xe3o utf-8 !#%{}[]()?;,.$@&"
];
$expecteds = [
    'este já está na codificação correta !#%{}[]()?;,.$@&',
    'este não está na codificação utf-8 !#%{}[]()?;,.$@&',
];
$result = [];
foreach ($inputs as $input) {
    $result[] = \CloudDFe\BancoCommon\Strings::normalize($input);
}
$split = str_split($expecteds[1]);

//1 => 'este não está na codificação utf-8'
//1 => 'este nâo está na codificação utf-8'
echo "aqui";
