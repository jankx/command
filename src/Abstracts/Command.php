<?php
namespace Jankx\Command\Abstracts;

abstract class Command
{
    abstract public function get_name();

    /**
     * Return command hook
     */
    abstract public function register_command();
}
