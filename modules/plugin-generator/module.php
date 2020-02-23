<?php
namespace ElementorPlugin\Modules\PluginGenerator;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends \ElementorPlugin\Core\Base\Module {

	public function __construct() {
		parent::__construct( __DIR__ );

		$this->initialize();
	}

	public function get_name() {
		return 'plugin-generator-module';
	}

	public function initialize() {
		// Add Plugin actions.
		add_action( 'elementor/controls/controls_registered', [ $this, 'init_controls' ] );
	}

	public function init_controls() {
		// TODO.
	}
}
