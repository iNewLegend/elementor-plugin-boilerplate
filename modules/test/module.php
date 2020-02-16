<?php
namespace ElementorCustomPlugin\Modules\Test;

use ElementorCustomPlugin\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends Module_Base {

	public function __construct() {
		parent::__construct();

		$this->initialize();
	}

	public function get_name() {
		return 'test module';
	}

	public function get_widgets() {
		$widgets = [];

		$widget_files = scandir( __DIR__ . '/widgets/' );

		foreach ( $widget_files as $widget_file ) {
			if ( '.' === $widget_file[0] ) {
				continue;
			}

			$widgets [] = ucfirst( str_replace( '.php', '', $widget_file ) );
		}

		return $widgets;
	}

	public function initialize() {
		// Add Plugin actions.
		add_action( 'elementor/controls/controls_registered', [ $this, 'init_controls' ] );
	}

	public function init_controls() {
		// TODO.
	}
}
