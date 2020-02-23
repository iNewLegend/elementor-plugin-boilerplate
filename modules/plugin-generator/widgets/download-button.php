<?php

namespace ElementorPlugin\Modules\PluginGenerator\Widgets;

// https://stackoverflow.com/a/42605439/12148027
function recursive_scan( $path ) {
	global $file_info;
	$path = rtrim( $path, '/' );
	if ( ! is_dir( $path ) ) {
		$file_info[] = $path;
	} else {
		$files = scandir( $path );
		foreach ( $files as $file ) {
			if ( '.' != $file && '..' != $file ) {
				recursive_scan( $path . '/' . $file );
			}
		}
	}
	return $file_info;
}

class DownloadButton extends \Elementor\Widget_Button {

	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );

		if ( isset( $_GET['download'] ) && ( 'active' === $_GET['download'] ) ) {
			// Enable output of HTTP headers
			$options = new \ZipStream\Option\Archive();
			$options->setSendHttpHeaders( true );

			// Create a new zipstream object
			$zip = new \ZipStream\ZipStream( 'example.zip', $options );

			$ignore_list = [ '/vendor/', '/.git/', '.map', '.lock', 'node_modules' ];
			$plugin_files = recursive_scan( \ElementorPlugin::get_path() );
			$plugin_files = array_filter( $plugin_files, function ( $plugin_file ) use ( $ignore_list ) {
				foreach ( $ignore_list as $ignore_item ) {
					if ( strstr( $plugin_file, $ignore_item ) ) {
						return false;
					}
				}
				return true;
			} );

			$plugin_files = array_unique( $plugin_files );
			foreach ( $plugin_files as $plugin_file ) {
				$zip->addFile(
					str_replace( realpath( './' ) . '/wp-content/plugins', '', $plugin_file ),
					file_get_contents( $plugin_file )
				);
			}

			$zip->finish();
		}
	}

	public function get_name() {
		return 'plugin generator';
	}

	public function get_title() {
		return 'Plugin generator button';
	}

	public function get_icon() {
		return 'fab fa-elementor';
	}

	public function get_categories() {
		return [ 'general' ];
	}

	public function get_settings_for_display( $setting_key = null ) {
		$settings = parent::get_settings_for_display( $setting_key );

		$settings['link'] = [];
		$settings['link']['url'] = '?download=active';

		return $settings;
	}

	protected function _register_controls() {
		parent::_register_controls();

		$this->remove_control( 'link' );
	}

}
