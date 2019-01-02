<?php
if ( ! class_exists( 'Foxy_Cli' ) ) {
	require_once dirname( __FILE__ ) . '/src/class-foxy-cli.php';
	Foxy_Cli::register_commands();
}
