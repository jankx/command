<?php

namespace Jankx\Command\Abstracts;

if (!defined('ABSPATH')) {
    exit('Cheatin huh?');
}

use Jankx\Command\Interfaces\Subcommand as InterfacesSubcommand;

abstract class Subcommand implements InterfacesSubcommand
{
    /**
     * @return array
     */
    public function parameters()
    {
        return [];
    }
}
