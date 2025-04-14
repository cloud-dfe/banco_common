<?php

//function onlyNumbers()
it('somente numeros', function (): void {
    $inputs = [
        '292',
        'AB2BC3',
        'jkdbcs',
        "\x08"
    ];

    $expecteds = [
        '292',
        '23',
        '',
        ''
    ];
    $result = [];
    foreach ($inputs as $input) {
        $result[] = \CloudDFe\BancoCommon\Strings::onlyNumbers($input);
    }
    expect($result)->toEqual($expecteds);
});

//function toAscii()
it('converter para ascii', function (): void {
    $inputs = [
        'são é caça à você',
        'àáââ éê í óôõ çÇ úü ÀÁÂÃ ÉÊ Í ÚÜ'
    ];
    $expecteds = [
        'sao e caca a voce',
        'aaaa ee i ooo cC uu AAAA EE I UU'
    ];
    $result = [];
    foreach ($inputs as $input) {
        $result[] = \CloudDFe\BancoCommon\Strings::toASCII($input);
    }
    expect($result)->toEqual($expecteds);
});

//function replaceUnacceptableCharacters()
it('substituir caracteres inaceitáveis', function (): void {
    $inputs = [
        "string \n com \r e com \t",
        'string com múltiplos         espaços    ',
        "string com \x00 \x01 \x10 \x1F \x7F caracteres de controle",
        "strings com 'aspas simples'",
        'strings com "aspas duplas"'
    ];
    $expecteds = [
        "string com e com",
        'string com múltiplos espaços',
        "string com caracteres de controle",
        "strings com aspas simples",
        'strings com aspas duplas'
    ];
    $result = [];
    foreach ($inputs as $input) {
        $result[] = \CloudDFe\BancoCommon\Strings::replaceUnacceptableCharacters($input);
    }
    expect($result)->toEqual($expecteds);
});

//function normalize()
it('converter string para UTF-8', function (): void {
    $inputs = [
        'este já está na codificação correta !#%{}[]()?;,.$@&',
        "este n\xe3o est\xe1 na codifica\xe7\xe3o utf-8"
    ];
    $expecteds = [
        'este já está na codificação correta !#%{}[]()?;,.$@&',
        'este não está na codificação utf-8',
    ];
    $result = [];
    foreach ($inputs as $input) {
        $result[] = \CloudDFe\BancoCommon\Strings::normalize($input);
    }
    expect($result)->toEqual($expecteds);
});

//function propertiesToLower()
it('converter propriedades para minúsculas', function (): void {
    $arr = [
        'tesT' => 123,
        'variAvel' => 'lsjkjsk',
        'SeiLa' => true,
        'compleXo' => [
            'seriaL' => 1,
            'SputiniK' => [
                'sateLITE' => 'Russo'
            ]
        ]
    ];
    $input = json_decode(json_encode($arr));
    $arr = [
        'test' => 123,
        'variavel' => 'lsjkjsk',
        'seila' => true,
        'complexo' => [
            'serial' => 1,
            'sputinik' => [
                'satelite' => 'Russo'
            ]
        ]
    ];
    $expected = json_decode(json_encode($arr));
    $result = \CloudDFe\BancoCommon\Strings::propertiesToLower($input);
    expect($result)->toEqual($expected);
});

//function equilizeParameters()
it('ajustar os parâmetros de entrada', function (): void {
    $possible = [
        'var1',
        'var2',
        'var3',
        'var4',
    ];

    $expected = (object)[
        'var1' => 'var1',
        'var2' => 'var2',
        'var3' => null,
        'var4' => null,
    ];
    $std = new stdClass();
    $std->var1 = 'var1';
    $std->var2 = 'var2';

    $result = \CloudDFe\BancoCommon\Strings::equilizeParameters($std, $possible, false);
    expect($result)->toEqual($expected);
});

//function equilizeParameters()
it('ajustar os parâmetros de entrada convertendo para ASCII os valores', function (): void {
    $possible = [
        'var1',
        'var2',
        'var3',
        'var4',
    ];

    $expected = (object)[
        'var1' => 'var1',
        'var2' => 'var2',
        'var3' => null,
        'var4' => null,
    ];
    $std = new stdClass();
    $std->var1 = 'var1';
    $std->var2 = 'var2';

    $result = \CloudDFe\BancoCommon\Strings::equilizeParameters($std, $possible, true);
    expect($result)->toEqual($expected);
});
