<?php
namespace Jankx\Command\Commands;

use Jankx\Command\Command;

class Option extends Command
{
    const COMMAND_NAME = 'option';

    public function get_name()
    {
        return static::COMMAND_NAME;
    }

    public function create()
    {
        echo 'create';
    }
}
