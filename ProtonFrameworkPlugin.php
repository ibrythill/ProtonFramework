<?php

 /**
 * Plugin Name: Proton Framework Plugin
 * Plugin URI: http://protonthemes.com/
 * Description: Proton Framework - A WordPress theme development framework. Best tools for your Wordpress site.
 * Version: 1.0
 * Author: Proton Themes
 * Author URI: http://protonthemes.com/
 * Text Domain: PROTON
 * Domain Path: languages/
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 */
//require_once plugin_dir_path( __FILE__ ) . 'includes/class-plugin-name-activator.php';

/**
 * The code that runs during plugin deactivation.
 */
//require_once plugin_dir_path( __FILE__ ) . 'includes/class-plugin-name-deactivator.php';

/** This action is documented in includes/class-plugin-name-activator.php */
//register_activation_hook( __FILE__, array( 'Plugin_Name_Activator', 'activate' ) );

/** This action is documented in includes/class-plugin-name-deactivator.php */
//register_deactivation_hook( __FILE__, array( 'Plugin_Name_Deactivator', 'deactivate' ) );

/**
 * The core plugin class that is used to define internationalization,
 * dashboard-specific hooks, and public-facing site hooks.
 */
require_once plugin_dir_path( __FILE__ ) . 'inc/proton.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */

add_action( 'plugins_loaded', array( '\Proton\Core', 'get_instance' ) );

