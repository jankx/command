<?php

namespace Jankx\Command\Commands\Option;

if (!defined('ABSPATH')) {
    exit('Cheatin huh?');
}

use Jankx\Command\Abstracts\Subcommand;

class CreateOptionCommand extends Subcommand
{
    public function get_name()
    {
        return 'create';
    }

    public function print_help()
    {
    }

    public function handle($args, $assoc_args)
    {
    }
}
