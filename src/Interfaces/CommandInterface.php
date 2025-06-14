<?php

namespace Jankx\Command\Interfaces;

interface CommandInterface extends BaseCommand
{
    public function addSubCommand(Subcommand $command);

    public function initSubCommands($thisCommand);

    public function registerSubCommands();
}
