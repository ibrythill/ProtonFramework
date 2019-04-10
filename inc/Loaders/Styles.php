<?php
namespace Proton\Loaders;

class Styles{

	/**
	* Holds the instance of this class.
	*/
	private static $instance;

	private $queued_styles = array();
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
		//add_action( 'wp_enqueue_scripts', 		array($this, 'wp_enqueue'), 99 );
		add_action( 'wp_print_footer_scripts',	array($this, 'wp_print'), 20 );
	}


	/**
	 * Tells WordPress to load the scripts needed for the framework using the wp_enqueue_script() function.
	 *
	 * @since  1.2.0
	 * @access public
	 * @return void
	 */
	public function wp_enqueue() {

		wp_enqueue_script( 'proton-requirejs', 		PROTON_ASSETS_URL . 'js/vendor/require.js', array(), PROTON_VERSION , true );
	}


	public function register($name, $path, $dep = array(), $priority = 10){
		$this->queued_styles[$name] = array('path'=> $path, 'require'=> $dep, 'priority'=> $priority, 'queued'=> false);
		return;
	}
	public function queue($name, $path = null, $dep = array(), $priority = 10){
		if(null != $path){
			$this->queued_styles[$name] = array('path'=> $path, 'require'=> $dep, 'priority'=> $priority, 'queued'=> true);
		}elseif(array_key_exists($name, $this->queued_styles)){
			$this->queued_styles[$name]['queued'] = true;
		}
		return;
	}

	/**
	 * proton_front_lab function.
	 *
	 * @access public
	 * @return void
	 */
	public function wp_print(){
		$queue = apply_filters('proton/assets/styles', $this->queued_styles);

		uasort($queue, function($a, $b) {
		    return $a['priority'] - $b['priority'];
		});

		?>
		<script type="text/javascript" id="loadcss">
		  // load a CSS file just before the script element containing this code
		  var proton_loadcss = [];
		  <?php
					foreach($queue as $key => $style){
				        $temp_path = $style['path'];
				        //echo "loadCSS( \"".$temp_path."\", document.getElementById(\"loadcss\") );"."\r\n";
				        echo "proton_loadcss.push(\"".$temp_path."\");"."\r\n";
			        }
					?>
		</script>
		<?php
	}


} // End Class


