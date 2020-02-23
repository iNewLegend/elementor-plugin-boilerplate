<?php

/**
 * Plugin Name: Elementor Plugin
 * Description: Elementor Plugin Description.
 * Plugin URI: https://elementor.com/
 * Author: Elementor.com
 * Version: 0.0.1
 * Author URI: https://elementor.com/
 *
 * Text Domain: elementor-plugin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

require 'vendor/autoload.php';

class ElementorPlugin {
	public static function get_version() {
		return '0.0.1';
	}

	public static function get_core_version_required() {
		return '2.8.0';
	}

	public static function get_core_version_recommended() {
		return '2.8.0';
	}

	public static function get_path() {
		return plugin_dir_path( __FILE__ );
	}

	public static function get_path_base() {
		return plugin_basename( __FILE__ );
	}

	public static function get_path_assets() {
		return self::get_path() . 'assets/';
	}

	public static function get_path_modules() {
		return self::get_path() . 'modules/';
	}

	public static function get_url() {
		return plugins_url( '/', __FILE__ );
	}

	public static function get_url_assets() {
		return self::get_url() . 'assets/';
	}

	public static function get_modules_url() {
		return self::get_url() . 'modules/';
	}

	public static function on_plugins_loaded() {
		load_plugin_textdomain( 'elementor-plugin' );

		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', function () {
				self::admin_notice_fail_load();
			} );

			return;
		}

		if ( ! version_compare( ELEMENTOR_VERSION, self::get_core_version_required(), '>=' ) ) {
			add_action( 'admin_notices',  function () {
				self::admin_notice_out_of_date();
			} );

			return;
		}

		if ( ! version_compare( ELEMENTOR_VERSION, self::get_core_version_recommended(), '>=' ) ) {
			add_action( 'admin_notices', function () {
				self::admin_notice_upgrade_recommendation();
			} );
		}

		require self::get_path() . 'plugin.php';
	}

	public static function admin_notice_fail_load() {
		$screen = get_current_screen();
		if ( isset( $screen->parent_file ) && 'plugins.php' === $screen->parent_file && 'update' === $screen->id ) {
			return;
		}

		$plugin = 'elementor/elementor.php';

		if ( _is_elementor_installed() ) {
			if ( ! current_user_can( 'activate_plugins' ) ) {
				return;
			}

			$activation_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin );

			$message = '<p>' . __( 'Elementor Plugin is not working because you need to activate the Elementor plugin.', 'elementor-custom-plugin' ) . '</p>';
			$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $activation_url, __( 'Activate Elementor Now', 'elementor-custom-plugin' ) ) . '</p>';
		} else {
			if ( ! current_user_can( 'install_plugins' ) ) {
				return;
			}

			$install_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=elementor' ), 'install-plugin_elementor' );

			$message = '<p>' . __( 'Elementor Plugin is not working because you need to install the Elementor plugin.', 'elementor-custom-plugin' ) . '</p>';
			$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $install_url, __( 'Install Elementor Now', 'elementor-custom-plugin' ) ) . '</p>';
		}

		echo '<div class="error"><p>' . $message . '</p></div>';
	}

	public static function admin_notice_out_of_date() {
		if ( ! current_user_can( 'update_plugins' ) ) {
			return;
		}

		$file_path = 'elementor/elementor.php';

		$upgrade_link = wp_nonce_url( self_admin_url( 'update.php?action=upgrade-plugin&plugin=' ) . $file_path, 'upgrade-plugin_' . $file_path );
		$message = '<p>' . __( 'Elementor Plugin is not working because you are using an old version of Elementor.', 'elementor-custom-plugin' ) . '</p>';
		$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $upgrade_link, __( 'Update Elementor Now', 'elementor-custom-plugin' ) ) . '</p>';

		echo '<div class="error">' . $message . '</div>';
	}

	public static function admin_notice_upgrade_recommendation() {
		if ( ! current_user_can( 'update_plugins' ) ) {
			return;
		}

		$file_path = 'elementor/elementor.php';

		$upgrade_link = wp_nonce_url( self_admin_url( 'update.php?action=upgrade-plugin&plugin=' ) . $file_path, 'upgrade-plugin_' . $file_path );
		$message = '<p>' . __( 'A new version of Elementor is available. For better performance and compatibility of Elementor Plugin, we recommend updating to the latest version.', 'elementor-custom-plugin' ) . '</p>';
		$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $upgrade_link, __( 'Update Elementor Now', 'elementor-custom-plugin' ) ) . '</p>';

		echo '<div class="error">' . $message . '</div>';
	}
}

add_action( 'plugins_loaded', function () {
	\ElementorPlugin::on_plugins_loaded();
} );

if ( ! function_exists( '_is_elementor_installed' ) ) {

	function _is_elementor_installed() {
		$file_path = 'elementor/elementor.php';
		$installed_plugins = get_plugins();

		return isset( $installed_plugins[ $file_path ] );
	}
}
