<?php

namespace Jankx\Command\Abstracts;

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
