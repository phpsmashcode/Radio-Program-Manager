<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://dev-appstation.pantheonsite.io/
 * @since             1.0.0
 * @package           Radio_Program_Manager
 *
 * @wordpress-plugin
 * Plugin Name:       Radio Program Manager
 * Plugin URI:        https://dev-appstation.pantheonsite.io/
 * Description:       Custom WordPress plugin to manage and display radio program’s schedule, including an import feature for program details via CSV. The schedule should allow unique broadcast times for each day of the week.
 * Version:           1.0.0
 * Author:            Hariprasad Vijayan
 * Author URI:        https://dev-appstation.pantheonsite.io//
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       radio-program-manager
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'RADIO_PROGRAM_MANAGER_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-radio-program-manager-activator.php
 */
function activate_radio_program_manager() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-radio-program-manager-activator.php';
	Radio_Program_Manager_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-radio-program-manager-deactivator.php
 */
function deactivate_radio_program_manager() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-radio-program-manager-deactivator.php';
	Radio_Program_Manager_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_radio_program_manager' );
register_deactivation_hook( __FILE__, 'deactivate_radio_program_manager' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-radio-program-manager.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_radio_program_manager() {

	$plugin = new Radio_Program_Manager();
	$plugin->run();

}
run_radio_program_manager();
