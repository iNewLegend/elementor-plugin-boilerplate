<?php
/**
 * Plugin Name: Elementor Custom Plugin
 * Description: Elementor Custom Plugin Description.
 * Plugin URI: https://elementor.com/
 * Author: Elementor.com
 * Version: 0.0.1
 * Author URI: https://elementor.com/
 *
 * Text Domain: elementor-custom-plugin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'ELEMENTOR_CUSTOM_PLUGIN_VERSION', '0.0.1' );

define( 'ELEMENTOR_CUSTOM_PLUGIN__FILE__', __FILE__ );
define( 'ELEMENTOR_CUSTOM_PLUGIN_PLUGIN_BASE', plugin_basename( ELEMENTOR_CUSTOM_PLUGIN__FILE__ ) );
define( 'ELEMENTOR_CUSTOM_PLUGIN_PATH', plugin_dir_path( ELEMENTOR_CUSTOM_PLUGIN__FILE__ ) );
define( 'ELEMENTOR_CUSTOM_PLUGIN_ASSETS_PATH', ELEMENTOR_CUSTOM_PLUGIN_PATH . 'assets/' );
define( 'ELEMENTOR_CUSTOM_PLUGIN_MODULES_PATH', ELEMENTOR_CUSTOM_PLUGIN_PATH . 'modules/' );
define( 'ELEMENTOR_CUSTOM_PLUGIN_URL', plugins_url( '/', ELEMENTOR_CUSTOM_PLUGIN__FILE__ ) );
define( 'ELEMENTOR_CUSTOM_PLUGIN_ASSETS_URL', ELEMENTOR_CUSTOM_PLUGIN_URL . 'assets/' );
define( 'ELEMENTOR_CUSTOM_PLUGIN_MODULES_URL', ELEMENTOR_CUSTOM_PLUGIN_URL . 'modules/' );

/**
 * Load gettext translate for our text domain.
 *
 * @since 1.0.0
 *
 * @return void
 */
function elementor_custom_load_plugin() {
	load_plugin_textdomain( 'elementor-custom-plugin' );

	if ( ! did_action( 'elementor/loaded' ) ) {
		add_action( 'admin_notices', 'elementor_custom_fail_load' );

		return;
	}

	$elementor_version_required = '2.8.0';
	if ( ! version_compare( ELEMENTOR_VERSION, $elementor_version_required, '>=' ) ) {
		add_action( 'admin_notices', 'elementor_custom_fail_load_out_of_date' );

		return;
	}

	$elementor_version_recommendation = '2.8.0';
	if ( ! version_compare( ELEMENTOR_VERSION, $elementor_version_recommendation, '>=' ) ) {
		add_action( 'admin_notices', 'elementor_custom_admin_notice_upgrade_recommendation' );
	}

	require ELEMENTOR_CUSTOM_PLUGIN_PATH . 'plugin.php';
}

add_action( 'plugins_loaded', 'elementor_custom_load_plugin' );

/**
 * Show in WP Dashboard notice about the plugin is not activated.
 *
 * @since 1.0.0
 *
 * @return void
 */
function elementor_custom_fail_load() {
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

		$message = '<p>' . __( 'Elementor Custom Plugin is not working because you need to activate the Elementor plugin.', 'elementor-custom-plugin' ) . '</p>';
		$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $activation_url, __( 'Activate Elementor Now', 'elementor-custom-plugin' ) ) . '</p>';
	} else {
		if ( ! current_user_can( 'install_plugins' ) ) {
			return;
		}

		$install_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=elementor' ), 'install-plugin_elementor' );

		$message = '<p>' . __( 'Elementor Custom Plugin is not working because you need to install the Elementor plugin.', 'elementor-custom-plugin' ) . '</p>';
		$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $install_url, __( 'Install Elementor Now', 'elementor-custom-plugin' ) ) . '</p>';
	}

	echo '<div class="error"><p>' . $message . '</p></div>';
}

function elementor_custom_fail_load_out_of_date() {
	if ( ! current_user_can( 'update_plugins' ) ) {
		return;
	}

	$file_path = 'elementor/elementor.php';

	$upgrade_link = wp_nonce_url( self_admin_url( 'update.php?action=upgrade-plugin&plugin=' ) . $file_path, 'upgrade-plugin_' . $file_path );
	$message = '<p>' . __( 'Elementor Custom Plugin is not working because you are using an old version of Elementor.', 'elementor-custom-plugin' ) . '</p>';
	$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $upgrade_link, __( 'Update Elementor Now', 'elementor-custom-plugin' ) ) . '</p>';

	echo '<div class="error">' . $message . '</div>';
}

function elementor_custom_admin_notice_upgrade_recommendation() {
	if ( ! current_user_can( 'update_plugins' ) ) {
		return;
	}

	$file_path = 'elementor/elementor.php';

	$upgrade_link = wp_nonce_url( self_admin_url( 'update.php?action=upgrade-plugin&plugin=' ) . $file_path, 'upgrade-plugin_' . $file_path );
	$message = '<p>' . __( 'A new version of Elementor is available. For better performance and compatibility of Elementor Custom Plugin, we recommend updating to the latest version.', 'elementor-custom-plugin' ) . '</p>';
	$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $upgrade_link, __( 'Update Elementor Now', 'elementor-custom-plugin' ) ) . '</p>';

	echo '<div class="error">' . $message . '</div>';
}

if ( ! function_exists( '_is_elementor_installed' ) ) {

	function _is_elementor_installed() {
		$file_path = 'elementor/elementor.php';
		$installed_plugins = get_plugins();

		return isset( $installed_plugins[ $file_path ] );
	}
}
