<?php
namespace ElementorCustomPlugin\Modules\Test;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends \Elementor\Core\Base\Module {

	public function __construct() {

	}

	public function get_name() {
		return 'test';
	}
}
