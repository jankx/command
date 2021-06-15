<?php
namespace Jankx\Command;

use WP_CLI;

use Jankx\Command\Command;
use Jankx\Command\Commands\Option;
use Jankx\Command\Commands\Cache;

class CLI
{
    const COMMAND_NAMESPACE = 'jankx';

    protected static $instance;

    protected $command_providers = array();

    protected $commands = array();

    public static function getInstance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    protected function __construct()
    {
        $this->bootstrap();
        $this->init_commands();
    }


    protected function bootstrap()
    {
        $default_jankx_commands = array(
            Option::class,
            Cache::class,
        );

        $this->command_providers = apply_filters(
            'jankx_command_providers',
            $default_jankx_commands
        );
    }

    protected function init_commands()
    {
        foreach ($this->command_providers as $cls_command) {
            $command = new $cls_command();
            if (!is_a($command, Command::class)) {
                continue;
            }
            $this->commands[$command->get_name()] = $command;
        }
        add_action('cli_init', array($this, 'register_commands'));
    }

    public function register_commands()
    {
        foreach ($this->commands as $command) {
            WP_CLI::add_command(
                sprintf('%s %s', static::COMMAND_NAMESPACE, $command->get_name()),
                $command
            );
        }
    }
}
