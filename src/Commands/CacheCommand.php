<?php
namespace Jankx\Command\Commands;

use WP_CLI;
use Jankx\Command\Command;

class Cache extends Command
{
    const COMMAND_NAME = 'cache';

    public function get_name()
    {
        return static::COMMAND_NAME;
    }

    private function clean_files($path)
    {
        $filesAndDirs = glob(sprintf('%s/*', $path));
        foreach ($filesAndDirs as $fileOrDir) {
            if (is_dir($fileOrDir)) {
                $this->clean_files($fileOrDir);
            } else {
                @unlink($fileOrDir);
            }
        }
        @rmdir($path);
    }

    protected function flush_css_cache()
    {
        WP_CLI::line(__('Clean cached CSS files', 'jankx'));
        $cssFiles = glob(sprintf('%s/{*,*/*}.css', rtrim(JANKX_CACHE_DIR, '/')), GLOB_BRACE);
        foreach ($cssFiles as $cssFile) {
            if (unlink($cssFile)) {
                WP_CLI::line(sprintf(__('%s is removed', 'jankx'), $cssFile));
            }
        }
    }

    protected function flush_templates_cache()
    {
        WP_CLI::line(__('Clean cached templates', 'jankx'));
        $templateDirs = array('twig');
        foreach ($templateDirs as $templateDir) {
            $cacheDir = sprintf('%s/%s', rtrim(JANKX_CACHE_DIR, '/'), $templateDir);
            if (!file_exists($cacheDir)) {
                continue;
            }
            WP_CLI::line(sprintf(__('Clean %s caches', 'jankx'), ucfirst($templateDir)));

            $this->clean_files($cacheDir);

            WP_CLI::line(sprintf(__('%s caching is clean up', 'jankx'), ucfirst($templateDir)));
        }
    }

    protected function print_help()
    {
        echo WP_ClI::colorize(__("Do you want to clean all cache please use \n%ywp jankx cache flush --all%n", 'jankx'));
    }

    public function flush($args, $assoc_args)
    {
        if (empty($args) && empty($assoc_args)) {
            return $this->print_help();
        }

        if (array_get($assoc_args, 'all')) {
            $assoc_args['css'] = true;
            $assoc_args['template'] = true;
        }

        if (array_get($assoc_args, 'css', false)) {
            $this->flush_css_cache();
        }
        if (array_get($assoc_args, 'template', false)) {
            $this->flush_templates_cache();
        }
    }
}
