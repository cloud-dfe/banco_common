<?php

declare(strict_types=1);

namespace CloudDFe\BancoCommon;

use stdClass;

class Strings
{
    /**
     * Altera o nome das propriedades do stdClass para minúsculas
     */
    public static function propertiesToLower(stdClass $data): stdClass
    {
        $properties = get_object_vars($data);
        $clone = new stdClass();
        foreach ($properties as $key => $value) {
            if ($value instanceof stdClass) {
                $value = self::propertiesToLower($value);
            }
            $nk = strtolower($key);
            $clone->{$nk} = $value;
        }
        return $clone;
    }

    /**
     * Monta objeto com todos os parâmetros possíveis e nulificando os parâmetros não informados
     * @param string[] $possible
     */
    public static function equilizeParameters(stdClass $std, array $possible, bool $replaceAccentedChars = false): stdClass
    {
        $arr = get_object_vars($std);
        foreach ($possible as $key) {
            if (!array_key_exists($key, $arr)) {
                $std->$key = null;
            } elseif (is_string($std->$key)) {
                $std->$key = self::replaceUnacceptableCharacters($std->$key);
                if ($replaceAccentedChars) {
                    $std->$key = self::toASCII($std->$key);
                }
            }
        }
        return $std;
    }

    /**
     * Filtra uma string deixando apenas numeros
     */
    public static function onlyNumbers(string $string): string
    {
        $new = preg_replace("/[^0-9]/", '', $string);
        return $new ?? '';
    }

    /**
     * Normaliza o texto para UTF-8 convertendo caracteres ISO 8859-1 que forem passados
     * NOTA: não remove "besteiras feitas pelos emitentes"
     */
    public static function normalize(string $input): string
    {
        //verifica se a string é UTF-8
        $validUTF8 = false !== mb_detect_encoding($input, 'UTF-8', true);
        if (!$validUTF8) {
            //a string contêm elementos não utf-8, presume-se se ISO
            $input = mb_convert_encoding($input, 'UTF-8', 'ISO-8859-1');
        }
        return $input;
    }

    /**
     * Remove todos os caracteres não aceitáveis nas strings
     */
    public static function replaceUnacceptableCharacters(string $input): string
    {
        $input = preg_replace('/[[:cntrl:]]/', '', $input);
        $input = str_replace(["\r", "\t", "\n"], ['','',''], $input ?? '');
        $input = str_replace("'", '', $input);
        $input = str_replace('"', '', $input);
        $input = preg_replace('/(\s\s+)/', ' ', $input);
        return trim($input ?? '');
    }

    /**
     * Converte para ASCII sem acentuação
     */
    public static function toASCII(string $input): string
    {
        $input = self::replaceUnacceptableCharacters($input);
        $input = self::normalize($input);
        $input = self::squashCharacters($input);
        return mb_convert_encoding($input, 'ascii');
    }

    /**
     * Remove a acentuação UTF-8 em português
     */
    protected static function squashCharacters(string $input): string
    {
        $aFind = [
            'á', 'à', 'ã', 'â', 'é', 'ê', 'í', 'ó', 'ô', 'õ', 'ö', 'ú', 'ü',
            'ç', 'Á', 'À', 'Ã', 'Â', 'É', 'Ê', 'Í', 'Ó', 'Ô', 'Õ', 'Ö', 'Ú', 'Ü', 'Ç'
        ];
        $aSubs = [
            'a', 'a', 'a', 'a', 'e', 'e', 'i', 'o', 'o', 'o', 'o', 'u', 'u',
            'c', 'A', 'A', 'A', 'A', 'E', 'E', 'I', 'O', 'O', 'O', 'O', 'U', 'U', 'C'
        ];
        return str_replace($aFind, $aSubs, $input);
    }
}
