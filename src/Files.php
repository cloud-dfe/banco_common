<?php

declare(strict_types=1);

namespace CloudDFe\BancoCommon;

class Files
{
    protected string $path;

    /**
     * Construto
     * @throws \Exception
     */
    public function __construct(string $path = null)
    {
        if (!empty($path)) {
            $this->makeFolder($path);
        }
    }

    /**
     * Cria um diretorio
     * @throws \Exception
     */
    public function makeFolder(string $folder): void
    {
        if (!is_dir($folder) && !mkdir($folder, 0777, true)) {
            throw new \Exception("Falhou ao tentar criar o path (verifique as permissões de escrita).");
        }
        if (substr($folder, -1) != DIRECTORY_SEPARATOR) {
            $folder .= DIRECTORY_SEPARATOR;
        }
        $this->path = $folder;
    }

    /**
     * Grava o arquivo e cria os diretorios inclusos no filename
     * @throws \Exception
     */
    public function put(string $filename, string $content): bool
    {
        $par = explode("/", $filename);
        if (count($par) > 1) {
            //tem mais diretorios
            $dir = '';
            for ($x = 0; $x < (count($par) - 1); $x++) {
                $dir .= $par[$x] . "/";
            }
            if (!is_dir($this->path . $dir)) {
                $path = $this->path . $dir;
                if (!mkdir($path, 0777, true)) {
                    throw new \Exception("Falhou ao tentar criar o path (verifique as permissões de escrita).");
                }
            }
        }
        if (file_put_contents($this->path . $filename, $content) === false) {
            throw new \Exception("Falhou ao tentar salvar o arquivo (verifique as permissões de escrita).");
        }
        return true;
    }

    /**
     * Remove o arquivo, se ele existir
     * @throws \Exception
     */
    public function delete(string $filename): bool
    {
        if (file_exists($filename)) {
            if (unlink($filename) === false) {
                throw new \Exception("Falhou ao tentar deletar o arquivo.");
            }
        } elseif (is_file($this->path . DIRECTORY_SEPARATOR . $filename)) {
            if (unlink($this->path . DIRECTORY_SEPARATOR . $filename) === false) {
                throw new \Exception("Falhou ao tentar deletar o arquivo.");
            }
        }
        return true;
    }

    /**
     * Lista o conteúdo da pasta indicada
     * @param string $folder
     * @return list<array<string, string>>
     */
    public function listContents(string $folder = ''): array
    {
        $new = [];
        if (is_dir($this->path . DIRECTORY_SEPARATOR . $folder)) {
            $list = ($folder === '' || $folder === '0')
                ? glob($this->path . "*.*")
                : glob($this->path . $folder . DIRECTORY_SEPARATOR . "*.*");
            if (is_array($list)) {
                foreach ($list as $f) {
                    $new[] = [
                        'type' => 'file',
                        'path' => $f
                    ];
                }
            }
        }
        return $new;
    }

    /**
     * Obtêm o timestamp da última alteração do arquivo indicado
     */
    public function getTimestamp(string $file): int
    {
        if (file_exists($file)) {
            $tm = filemtime($file);
            if ($tm !== false) {
                return $tm;
            }
        }
        return 0;
    }

    /**
     * Verifica que o arquivo ou pasta existe
     */
    public function has(string $path): bool
    {
        if (is_dir($path)) {
            return true;
        }
        if (is_file($path)) {
            return true;
        }
        return is_file($this->path . DIRECTORY_SEPARATOR . $path);
    }
}
