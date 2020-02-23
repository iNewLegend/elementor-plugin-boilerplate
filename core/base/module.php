<?php
namespace ElementorPlugin\Core\Base;

use ElementorPlugin\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

abstract class Module extends \Elementor\Core\Base\Module {

	/**
	 * @var string
	 */
	private $module_dir_path;

	public function __construct( string $module_dir_path ) {
		$this->module_dir_path = $module_dir_path;

		add_action( 'elementor/widgets/widgets_registered', [ $this, 'init_widgets' ] );
	}

	public function get_widgets() {
		$widgets = [];

		$widgets_dir_path = $this->module_dir_path . '/widgets/';

		if ( ! is_dir( $widgets_dir_path ) ) {
			return $widgets;
		}

		$widget_files = scandir( $widgets_dir_path );

		foreach ( $widget_files as $widget_file ) {
			if ( '.' === $widget_file[0] ) {
				continue;
			}

			$widget_name = ucfirst( str_replace( '.php', '', $widget_file ) );

			$pos = strpos( $widget_name, '-' );
			if ( $pos ) {
				$widget_name[ $pos ] = ucfirst( $widget_name[ $pos + 1 ] );
				$widget_name = substr_replace( $widget_name, '', $pos + 1, 1 );
			}

			$widgets [] = $widget_name;
		}

		return $widgets;
	}

	public function init_widgets() {
		$widget_manager = Plugin::elementor()->widgets_manager;

		foreach ( $this->get_widgets() as $widget ) {
			$class_name = $this->get_reflection()->getNamespaceName() . '\Widgets\\' . $widget;

			$widget_manager->register_widget_type( new $class_name() );
		}
	}
}
