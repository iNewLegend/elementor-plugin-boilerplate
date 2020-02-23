<?php
namespace ElementorPlugin\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

final class ModulesManager {
	/**
	 * @var \ElementorPlugin\Core\Base\Module[]
	 */
	private $modules = [];

	public function __construct() {
		$modules_files = scandir( __DIR__ . '/../modules/' );

		foreach ( $modules_files as $module_file ) {
			if ( '.' === $module_file[0] || 'assets' === $module_file ) {
				continue;
			}

			$module_name = str_replace( '.php', '', $module_file );

			$class_name = str_replace( '-', ' ', $module_name );
			$class_name = str_replace( ' ', '', ucwords( $class_name ) );
			$class_name = __NAMESPACE__ . '\\Modules\\' . $class_name . '\Module';
			$class_name = str_replace( '\\Core', '', $class_name );

			/** @var \ElementorPlugin\Core\Base\Module $class_name */
			if ( $class_name::is_active() ) {
				$this->modules[ $module_name ] = $class_name::instance();
			}
		}
	}

	/**
	 * @param string $module_name
	 *
	 * @return \ElementorPlugin\Core\Base\Module|\ElementorPlugin\Core\Base\Module[]|null
	 */
	public function get_modules( $module_name ) {
		if ( $module_name ) {
			if ( isset( $this->modules[ $module_name ] ) ) {
				return $this->modules[ $module_name ];
			}

			return null;
		}

		return $this->modules;
	}
}
