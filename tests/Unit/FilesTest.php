<?php

use CloudDFe\BancoCommon\Files;

//construct
it('criar um diretorio ao construir a classe', function (): void {
    $temp = sys_get_temp_dir();
    $files = new Files($temp . DIRECTORY_SEPARATOR . 'testes');
    $response = is_dir($temp . DIRECTORY_SEPARATOR . 'testes');
    expect($response)->toBeTrue();
});


//function makeFolder
it('criar um diretorio', function (): void {
    $temp = sys_get_temp_dir();
    $files = new Files();
    $files->makeFolder($temp . DIRECTORY_SEPARATOR . 'testes');
    $response = is_dir($temp . DIRECTORY_SEPARATOR . 'testes');
    expect($response)->toBeTrue();
});

//function makeFolder exception
it('impossivel criar um diretorio', function (): void {
    $files = new Files();
    //tentar criar uma pasta no diretorio raiz do sistema
    $files->makeFolder( DIRECTORY_SEPARATOR . 'testes');
})->throws(Exception::class, 'Falhou ao tentar criar o path (verifique as permissões de escrita)');


//function put()
it ('salvar um arquivo', function (): void {
    $temp = sys_get_temp_dir();
    $path = $temp . DIRECTORY_SEPARATOR . 'testes';
    $files = new Files($path);
    $content = "arquivo de teste";
    $filename = 'teste.txt';
    $files->put($filename, $content);
    $response = is_file($path . DIRECTORY_SEPARATOR . $filename);
    expect($response)->toBeTrue();
});

//function put() exception
it ('impossivel salvar um arquivo criando um novo folder sem permissão', function (): void {
    $files = new Files('/');
    $content = "arquivo de teste";
    $filename = '/novo/teste.txt';
    $files->put($filename, $content);
})->throws(Exception::class, 'Falhou ao tentar criar o path (verifique as permissões de escrita).');


//function put() exception
it ('impossivel salvar um arquivo', function (): void {
    $files = new Files('/opt');
    $content = "arquivo de teste";
    $filename = 'teste.txt';
    $files->put($filename, $content);
})->throws(Exception::class, 'Falhou ao tentar salvar o arquivo (verifique as permissões de escrita).');

//function has()
it ('existe um arquivo já criado na pasta', function (): void {
    $temp = sys_get_temp_dir();
    $path = $temp . DIRECTORY_SEPARATOR . 'testes';
    $files = new Files($path);
    $filename = 'teste.txt';
    $response = $files->has($filename);
    expect($response)->toBeTrue();
});

//function listContents()
it ('lista os arquivos do caminho indicado', function (): void {
    $temp = sys_get_temp_dir();
    $path = $temp . DIRECTORY_SEPARATOR . 'testes';
    $files = new Files($path);
    $lista = $files->listContents();
    expect($lista)->toBeArray();
});

//function getTimestamp()
it ('obtem o timestamp do arquivo', function (): void {
    $temp = sys_get_temp_dir();
    $path = $temp . DIRECTORY_SEPARATOR . 'testes';
    $files = new Files($path);
    $filename = 'teste.txt';
    $response = $files->has($filename);
    $timestamp = $files->getTimestamp($path . DIRECTORY_SEPARATOR . $filename);
    expect($timestamp)->toBeInt();
});

//function delete()
it ('deleta o arquivo criado na pasta', function (): void {
    $temp = sys_get_temp_dir();
    $path = $temp . DIRECTORY_SEPARATOR . 'testes';
    $files = new Files($path);
    $filename = 'teste.txt';
    $files->delete($path . DIRECTORY_SEPARATOR . $filename);
    $response = $files->has($filename);
    expect($response)->toBeFalse();
});






