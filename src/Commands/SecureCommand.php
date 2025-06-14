<?php

namespace Jankx\Command\Commands;

if (!defined('ABSPATH')) {
    exit('Cheatin huh?');
}

use Jankx\Command\Abstracts\Command;

class SecureCommand extends Command
{
    const COMMAND_NAME = 'secure';

    public function get_name()
    {
        return static::COMMAND_NAME;
    }

    public function print_help()
    {
    }


    /**
     * Summary of lookingForPhpFiles
     * @param string|array $file
     * @return array
     */
    protected function lookingForPhpFiles($paths)
    {
        $files = [];

        if (is_string($paths)) {
            $paths = [$paths];
        }

        $excludeDirectories = ['node_modules'];

        foreach ($paths as $file) {
            if (is_dir($file)) {
                if (in_array(basename($file), $excludeDirectories)) {
                    continue;
                }

                $phpFiles = glob(sprintf('%s%s{*}.php', $file, DIRECTORY_SEPARATOR), GLOB_BRACE);
                $files = array_merge($files, $phpFiles);

                $dirs = basename($file) !== 'vendor'
                    ? glob(sprintf('%s%s*', $file, DIRECTORY_SEPARATOR), GLOB_ONLYDIR)
                    : [ $file . DIRECTORY_SEPARATOR . 'jankx'];
                $files = array_merge($files, $this->lookingForPhpFiles($dirs));
            } else {
                if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                    $files[] = $file;
                }
            }
        }

        return $files;
    }

    protected function checkFileStillNotCheckLoader($phpFile)
    {
        $file_contents = file_get_contents($phpFile);

        return strpos($file_contents, "defined('ABSPATH')") === false;
    }

    protected function getCheckLoaderContent()
    {
        ob_start();
        ?>if (!defined('ABSPATH')) {
    exit('Cheatin huh?');
}<?php
        return ob_get_clean();
    }

    /**
     * Summary of findRelaceIndexFromExistsContent
     *
     * @param array $lines
     *
     * @return bool|float|int
     */
    protected function findRelaceIndexFromExistsContent($lines)
    {
        foreach ($lines as $index => $line) {
            if (strpos($line, 'namespace ') === 0) {
                return $index + 1;
            }
        }
        if (trim($lines[0]) === '<?php') {
            return 0;
        }

        return false;
    }

    protected function writeCheckLoaderIsWordPress($phpFile)
    {
        if (!$this->checkFileStillNotCheckLoader($phpFile)) {
            return;
        }
        $lines = file($phpFile);

        $replaceIndex = $this->findRelaceIndexFromExistsContent($lines);

        if ($replaceIndex > 0) {
            $lines[$replaceIndex] = PHP_EOL . $this->getCheckLoaderContent() . PHP_EOL . $lines[$replaceIndex];
        } elseif ($replaceIndex === 0) {
            $lines[0] = '<?php' . PHP_EOL . $this->getCheckLoaderContent() . PHP_EOL;
        } else {
            array_unshift($lines, '<?php ' . PHP_EOL . $this->getCheckLoaderContent() . PHP_EOL . ' ?>' . PHP_EOL);
        }

        @file_put_contents($phpFile, $lines);
    }

    public function handle($args, $assoc_args)
    {
        $dirs = [get_template_directory()];
        if (is_child_theme()) {
            $dirs[] = get_stylesheet_directory();
        }
        $phpFiles = $this->lookingForPhpFiles($dirs);
        foreach ($phpFiles as $phpFile) {
            if (!file_exists($phpFile)) {
                continue;
            }
            $this->writeCheckLoaderIsWordPress($phpFile);
        }
    }
}
