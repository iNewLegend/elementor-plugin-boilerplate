<?php
namespace ElementorCustomPlugin\Core\Editor;

use Elementor\Core\Base\App;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Editor extends App {
	public function get_name() {
		return 'custom-plugin-editor';
	}

	public function __construct() {
		add_action( 'elementor/init', [ $this, 'on_elementor_init' ] );
		add_action( 'elementor/editor/init', [ $this, 'on_elementor_editor_init' ] );
		add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'enqueue_editor_styles' ] );
		add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'enqueue_editor_scripts' ] );
	}

	public function get_init_settings() {
		$settings = [
			'i18n' => [],
			'urls' => [
				'modules' => ELEMENTOR_CUSTOM_PLUGIN_MODULES_URL,
			],
		];

		$settings = apply_filters( 'elementor_custom_plugin/editor/localize_settings', $settings );

		return $settings;
	}

	public function enqueue_editor_styles() {
		// Load editor.css
		wp_enqueue_style(
			'elementor-plugin',
			$this->get_css_assets_url( 'editor', null, 'default', true ),
			[
				'elementor-editor',
			],
			ELEMENTOR_CUSTOM_PLUGIN_VERSION
		);
	}

	public function enqueue_editor_scripts() {
		// Load editor.js
		wp_enqueue_script(
			'elementor-plugin',
			$this->get_js_assets_url( 'editor/editor' ),
			[
				'backbone-marionette',
				'elementor-common',
				'elementor-editor-modules',
			],
			ELEMENTOR_CUSTOM_PLUGIN_VERSION,
			true
		);

		$this->print_config( 'elementor-custom-plugin' );
	}

	public function on_elementor_init() {

	}

	public function on_elementor_editor_init() {

	}

	protected function get_assets_base_url() {
		return ELEMENTOR_CUSTOM_PLUGIN_URL;
	}
}
