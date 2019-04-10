<?php
namespace Proton\Loaders;

class Assets{

	/**
	* Holds the instance of this class.
	*/
	private static $instance;

	/**
	 * Returns the instance.
	 *
	 * @since  1.1.0
	 * @access public
	 * @return object
	 */
	public static function get_instance() {

		if ( !self::$instance ){
			self::$instance = new self;
		}
		return self::$instance;
	}

	/**
	 * Template Constructor.
	*/
	private function __construct(){
		# Register Proton Core scripts.
		add_action( 'admin_enqueue_scripts', 	array($this, 'admin_enqueue'), 11 );

		# Load Proton Core scripts.
		add_action( 'wp_enqueue_scripts', 		array($this, 'front_enqueue'), 150 );
		add_filter( 'rocket_exclude_defer_js', array($this, 'exclude_files') );
		//add_filter( 'script_loader_tag', 		array($this, 'add_requirejs'), 99, 3 );
		//add_action( 'wp_print_footer_scripts',	array($this, 'front_lab'), 45 );
	}

	/**
	 * Registers JavaScript files for the framework.  This function merely registers scripts with WordPress using
	 * the wp_register_script() function.  It does not load any script files on the site.  If a theme wants to register
	 * its own custom scripts, it should do so on the 'wp_enqueue_scripts' hook.
	 *
	 * @since  1.2.0
	 * @access public
	 * @return void
	 */
	public function admin_enqueue() {

		wp_enqueue_style( 'proton-admin-styles', 	PROTON_ASSETS_URL . 'css/proton-admin.css', false, PROTON_VERSION);
		wp_enqueue_style( 'proton-themes', 			PROTON_ASSETS_URL . 'css/proton-themes.css', false, PROTON_VERSION);
		//wp_enqueue_style( 'fontawesome', 	'https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css', false, PROTON_VERSION);

		//wp_enqueue_script( 'wp-util' );
		//wp_enqueue_script( 'backbone' );
		//wp_enqueue_script( 'proton-framework', 		PROTON_ASSETS_URL . 'js/proton.init.min.js', array(/*'backbone', 'jquery', 'wp-util', 'jquery-ui-tabs'*/), PROTON_VERSION  );

		wp_localize_script( 'proton-framework', 'proton_var', array(
	                    'proton_nonce' => wp_create_nonce( "PROTON" ),
	                    'proton_js_base' => PROTON_ASSETS_URL . 'js/'
	            ));
	}


	/**
	 * Tells WordPress to load the scripts needed for the framework using the wp_enqueue_script() function.
	 *
	 * @since  1.2.0
	 * @access public
	 * @return void
	 */
	public function front_enqueue() {
		// Load the comment reply script on singular posts with open comments if threaded comments are supported.
		//proton_enqueue_style( 'proton-framework', 	PROTON_ASSETS_URL . 'css/proton-framework.css', 5);
		//wp_enqueue_script( 'loadCSS', 		PROTON_ASSETS_URL . 'js/vendor/loadCSS.min.js', array(), PROTON_VERSION  );
		wp_enqueue_script( 'proton-framework', 		PROTON_ASSETS_URL . 'js/proton.init.min.js', array('jquery'), PROTON_VERSION , true );
		//wp_enqueue_script( 'proton-framework', 		PROTON_ASSETS_URL . 'js/vendor/require.min.js', false, PROTON_VERSION , true );

		wp_localize_script( 'proton-framework', 'proton',
			apply_filters( 'proton/assets/localize', array(
                'nonce_key' 	=> wp_create_nonce( "PROTON" ),
                'basejs' 		=> wp_parse_url(PROTON_ASSETS_URL)['path'] . 'js/'
            ))
	    );
	}

	public function exclude_files( $excluded_files = array() ) {

	/**
	 * EDIT THIS:
	 * Edit below line as needed to exclude files.
	 * To exclude mupltiple files, copy the entire line into a new line for each file you wish you exclude.
	 */
	$excluded_files[] = '/wp-content/plugins/ProtonFrameworkPlugin/assets/js/proton.init.min.js';
	// STOP EDITING

	return $excluded_files;
}


	/**
	 * add_requirejs function.
	 *
	 * @access public
	 * @param mixed $tag
	 * @param mixed $handle
	 * @param mixed $src
	 * @return $tag
	 */
	public function add_requirejs( $tag, $handle, $src ) {
	    if ( 'proton-framework' === $handle ) {
	        $tag = '<script type="text/javascript" src="' . esc_url( $src ) . '"></script>'."\n".'<script type="text/javascript" src="' .  PROTON_ASSETS_URL . 'js/proton.config.min.js"></script>'."\n";
	    }
	    return $tag;
	}

	/**
	 * proton_front_lab function.
	 *
	 * @access public
	 * @return void
	 */
	public function front_lab(){
		global $Proton;
		if(!isset($Proton->scripts) || !is_array($Proton->scripts)){
			$Proton->scripts = array();
		}
		$output = '<script>';
		//$output .= '$LAB.setOptions({AlwaysPreserveOrder:true})';
		$output .= '$LAB';
		//$output .= '.wait()';
		$output .= '.script("' . PROTON_ASSETS_URL . 'js/proton.framework.min.js")';
		$queue = apply_filters('proton/assets/scripts', $Proton->scripts);

		usort($queue, array($this,"sort"));

		foreach($queue as $script){
			if($script[3] > 1){
				$output .= '.wait()';
			}
			$output .= '.script("' . $script[0] . '")';
		}

		$output .= '</script>';
		echo $output;
	}

	//sort helper function
	/**
	 * proton_enqueue_script_sort function.
	 *
	 * @access public
	 * @param mixed $a
	 * @param mixed $b
	 * @return void
	 */
	function sort($a, $b) {
	  return $a[3] - $b[3];
	}


} // End Class


