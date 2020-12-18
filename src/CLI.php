<?php
namespace Jankx\Command;

use WP_CLI;

use Jankx\Command\Option;
use Jankx\Command\Abstracts\Command;

class CLI
{
    const NAMESPACE = 'jankx';

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
            Option::class
        );
        $this->command_providers = apply_filters(
            'jankx_command_providers',
            $default_jankx_commands
        );
    }

    protected function init_commands()
    {
        $this->register_root_command();
        foreach ($this->command_providers as $cls_command) {
            $command = new $cls_command();
            if (!is_a($command, Command::class)) {
                continue;
            }
            $this->commands[$command->get_name()] = $command;
        }
        add_action('after_setup_theme', array($this, 'register_commands'));
    }

    public function register_root_command()
    {
        WP_CLI::add_command(static::NAMESPACE, array($this, 'print_help'));
    }

    public function print_help()
    {
    }

    public function register_commands()
    {
        foreach ($this->commands as $name => $command) {
            // Execute register command
            $args = $command->register_command();

            if (is_callable($args)) {
                WP_CLI::add_command(
                    sprintf('%s %s', static::NAMESPACE, $name),
                    $args
                );
            }
        }
    }
}
