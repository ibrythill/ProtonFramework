<?php
/**
 * Proton - A WordPress theme development framework.
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU
 * General Public License as published by the Free Software Foundation; either version 2 of the License,
 * or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * You should have received a copy of the GNU General Public License along with this program; if not, write
 * to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 *
 * @package   Proton
 * @version   1.0.6
 * @author    Karol Pentak <admin@ibrythill.com>
 * @copyright Copyright (c) 2010 - 2016, Karol "Ibrythill" Pentak
 * @link      http://ibrythill.com/
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

namespace Proton;

use Proton\Media\Image 			as Image;
use Proton\Helpers\Ajax 		as Ajax;
use Proton\Helpers\Twig 		as Twig;
use Proton\Loaders\Assets 		as Assets;

/**
 * Final Core class.
 *
 * @final
 */
final class Core{

	/**
	 * Holds the instance of this class.
	 *
	 * (default value: null)
	 *
	 * @var mixed
	 * @access private
	 * @static
	 */
	private static $instance = null;

	/**
	 * slug
	 *
	 * (default value: 'proton')
	 *
	 * @var string
	 * @access protected
	 */
	protected $slug = 'proton';

	/**
	 * opt
	 *
	 * (default value: null)
	 *
	 * @var mixed
	 * @access public
	 * @static
	 */
	public static $opt = null;

	/**
	 * Returns the instance.
	 *
	 * @since  1.1.0
	 * @access public
	 * @return object
	 */
	public static function get_instance() {

		if ( is_null( self::$instance ) ){
			self::$instance = new self;
			self::$instance->constants();
			self::$instance->core();
			self::$instance->setup();
		}
		return self::$instance;
	}


	private function __construct(){
		global $Proton;

		/* Set up an empty class for the global $proton object. */
		$Proton = new \stdClass;

	}



	/**
	 * Defines the constant paths for use within the core framework.
	 *
	 * @since 0.9.0
	 */
	private function constants() {


		/* Sets the framework version number. */
		define( 'PROTON_VERSION', 		'4.0' );

		/* Sets the framework slug. */
		define( 'PROTON_SLUG', 			$this->slug );

		/* Sets the framework update url. */
		define( 'PROTON_API', 			'http://api.protonthemes.com/' );

		/* PROTON */
		define( 'DS', 					DIRECTORY_SEPARATOR );
		define( 'PROTON_DIR', 			dirname(dirname(__FILE__)) . DS );
		define( 'PROTON_URL', 			dirname(plugin_dir_url(__FILE__)) . '/' );

		/* THEME */
		define( 'THEME', 				get_template_directory() . DS);
		define( 'THEME_URL', 			get_template_directory_uri() . '/');

		/* CLASSES */
		define( 'PROTON_INC', 			trailingslashit( PROTON_DIR . 'inc' ));
		define( 'PROTON_INC_URL', 		trailingslashit( PROTON_URL . 'inc' ));

		/* FIELDS */
		define( 'PROTON_FIELDS', 	 	trailingslashit( PROTON_DIR . 'fields' ));
		define( 'PROTON_FIELDS_URL', 	trailingslashit( PROTON_URL . 'fields' ));

		/* ADMIN */
		define( 'PROTON_ADMIN', 		trailingslashit( PROTON_DIR . 'admin' ));
		define( 'PROTON_ADMIN_URL', 	trailingslashit( PROTON_URL . 'admin' ));

		/* ASSETS */
		define( 'PROTON_ASSETS', 	 	trailingslashit( PROTON_DIR . 'assets' ));
		define( 'PROTON_ASSETS_URL', 	trailingslashit( PROTON_URL . 'assets' ));

		/* EXTENSIONS */
		define( 'PROTON_EXT', 	 		trailingslashit( PROTON_DIR . 'ext' ));
		define( 'PROTON_EXT_URL', 		trailingslashit( PROTON_URL . 'ext' ));

		/* TEMPLATES */
		define( 'PROTON_TMPL', 	 		trailingslashit( PROTON_DIR . 'tmpl' ));
		define( 'PROTON_TMPL_URL', 		trailingslashit( PROTON_URL . 'tmpl' ));

		/* WIDGETS */
		//define( 'WPWIDGETS', trailingslashit( PLUGINS)."widgets" . DS);

		/* SHORTCODES */
		//define( 'SHORTCODES', trailingslashit(MODULES)."shortcodes" . DS);

	}

	/**
	 * setup function.
	 *
	 * @access private
	 * @return void
	 */
	private function setup() {

		/* Load localization files. */
		add_action( 'init', 	 			array( $this, 'i18n' ) );

		/* Initialize classes. */
		add_action( 'after_setup_theme', 	array( $this, 'init' ), 87 );
	}

	/**
	 * Loads the core framework classes and functions. These files are needed before loading anything else in the
	 * framework because they have required functions for use.
	 *
	 * @since 1.8.0
	 */
	private function core() {
		require_once( PROTON_INC . 'autoload.php' );

		require_once( PROTON_INC . 'functions-helpers.php' );
		require_once( PROTON_INC . 'functions-compat.php' );

		if(is_admin()){
			//require_once( PROTON_INC . 'class-opt.php' );

			//require_once( PROTON_INC . 'class-customizer-control.php' );
			//require_once( PROTON_INC . 'class-pointer.php');

			//require_once( PROTON_INC . 'class-update.php' );
			require_once( PROTON_INC . 'functions-htaccess.php' );
			require_once( PROTON_INC . 'functions-tinymce.php' );

		}else{

			require_once( PROTON_INC . 'functions-id.php' );
			require_once( PROTON_INC . 'functions-limits.php' );
			//require_once( PROTON_INC . 'functions-head.php' );
			require_once( PROTON_INC . 'functions-media.php' );
			require_once( PROTON_INC . 'functions-other.php' );

		}

		//require_once( PROTON_INC . 'class-sidebars.php' );

		/*shortcodes*
		include(SHORTCODES . 'mygallery.php');
		include(SHORTCODES . 'shortcodes.php');
		*/
	}

	/**
	 * Loads plugin translation files.
	 *
	 * @since  2.2.0
	 * @access public
	 * @return void
	 */
	public function i18n() {

		$domain = $this->slug;
		$locale = apply_filters( 'proton/locale', get_locale(), $domain );

		//$loaded = load_textdomain( $domain, trailingslashit( PROTON_DIR ) . 'languages/' . $domain . '-' . $locale . '.mo' );

		load_plugin_textdomain( $domain, false, dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages' );

        //load_plugin_textdomain( $domain, FALSE, trailingslashit( PROTON_DIR ) . 'languages' );
		//wp_die($loaded);


	}


	/**
	 * Initialize framework clsses.
	 *
	 * @since 2.2.0
	 */

	public function init(){

		// proton classes init

		Image::get_instance();

		Ajax::get_instance();

		Assets::get_instance();

		Twig::get_instance();


		if(is_admin()){
			//if(class_exists('_Proton__Pointers'))
				//_Proton__Pointers::get_instance();


			/*if(class_exists('_Proton__Opt_Meta'))
				_Proton__Opt_Meta::get_instance();*/


			/*if(class_exists('_Proton__Opt_Menu'))
				_Proton__Opt_Menu::get_instance();


			if(class_exists('_Proton__Opt_Term'))
				_Proton__Opt_Term::get_instance();


			if(class_exists('_Proton__Opt_User'))
				_Proton__Opt_User::get_instance();


			if(class_exists('_Proton__Templates'))
				_Proton__Templates::get_instance();
*/

		}else{



		}

		require_once( PROTON_INC . 'config.php' );
	}

	public static function register_opt($opt){
		self::$opt = $opt;
	}



} // End class



