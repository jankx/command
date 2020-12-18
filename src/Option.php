<?php
namespace Jankx\Command;

use WP_CLI;
use Jankx\Command\CLI;
use Jankx\Command\Abstracts\Command;

class Option extends Command
{
    const COMMAND_NAME = 'option';

    public function get_name()
    {
        return static::COMMAND_NAME;
    }

    public function register_command()
    {
        return array();
    }

    public function
}
