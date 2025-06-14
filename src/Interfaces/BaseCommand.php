<?php

namespace Jankx\Command\Interfaces;

if (!defined('ABSPATH')) {
    exit('Cheatin huh?');
}

interface BaseCommand
{
    public function handle($args, $assoc_args);

    public function get_name();

    public function print_help();

    /**
     * @return array
     */
    public function parameters();
}
