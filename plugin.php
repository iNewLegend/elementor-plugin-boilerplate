<?php
namespace ElementorPlugin;

use ElementorPlugin\Core\ModulesManager;
use ElementorPlugin\Core\Editor\Editor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Plugin {
	/**
	 * @var Plugin
	 */
	private static $_instance;

	/**
	 * @var ModuleManager
	 */
	public $modules_manager;

	/**
	 * @var Editor
	 */
	private $editor;

	private $classes_aliases = [];

	public static function get_title() {
		return 'elementor custom plugin title';
	}

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * @return \Elementor\Plugin
	 */
	public static function elementor() {
		return \Elementor\Plugin::$instance;
	}

	private function __construct() {
		spl_autoload_register( [ $this, 'autoload' ] );

		$this->setup_hooks();

		$this->editor = new Editor();

		//if ( is_admin() ) {
		//	$this->admin = new Admin();
		//}
	}

	public function autoload( $class ) {
		if ( 0 !== strpos( $class, __NAMESPACE__ ) ) {
			return;
		}

		$has_class_alias = isset( $this->classes_aliases[ $class ] );

		// Backward Compatibility: Save old class name for set an alias after the new class is loaded
		if ( $has_class_alias ) {
			$class_alias_name = $this->classes_aliases[ $class ];
			$class_to_load = $class_alias_name;
		} else {
			$class_to_load = $class;
		}

		if ( ! class_exists( $class_to_load ) ) {
			$filename = strtolower(
				preg_replace(
					[ '/^' . __NAMESPACE__ . '\\\/', '/([a-z])([A-Z])/', '/_/', '/\\\/' ],
					[ '', '$1-$2', '-', DIRECTORY_SEPARATOR ],
					$class_to_load
				)
			);
			$filename = \ElementorPlugin::get_path() . $filename . '.php';

			if ( is_readable( $filename ) ) {
				include( $filename );
			}
		}

		if ( $has_class_alias ) {
			class_alias( $class_alias_name, $class );
		}
	}

	public function on_elementor_init() {
		$this->modules_manager = new ModulesManager();

		do_action( 'elementor_custom_plugin/init' );
	}

	private function setup_hooks() {
		add_action( 'elementor/init', [ $this, 'on_elementor_init' ] );
	}
}

if ( ! defined( 'ELEMENTOR_PRO_TESTS' ) ) {
	// In tests we run the instance manually.
	Plugin::instance();
}
