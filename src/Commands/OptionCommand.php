<?php

namespace Jankx\Command\Commands;

if (!defined('ABSPATH')) {
    exit('Cheatin huh?');
}

use Jankx\Command\Abstracts\Command;

class OptionCommand extends Command
{
    const COMMAND_NAME = 'option';

    public function get_name()
    {
        return static::COMMAND_NAME;
    }

    public function print_help()
    {
    }
}
