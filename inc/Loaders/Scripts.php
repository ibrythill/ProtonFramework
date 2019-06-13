<?php
namespace Proton\Loaders;

class Scripts{

	/**
	* Holds the instance of this class.
	*/
	private static $instance;

	private $queued_scripts = array();
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

	public function register($name, $path, $dep = array(), $priority = 10){
		$this->queued_scripts[$name] = array('path'=> $path, 'require'=> $dep, 'priority'=> $priority, 'queued'=> false);
		return;
	}
	public function queue($name, $path = null, $dep = array(), $priority = 10){
		if(null != $path){
			$this->queued_scripts[$name] = array('path'=> $path, 'require'=> $dep, 'priority'=> $priority, 'queued'=> true);
		}elseif(array_key_exists($name, $this->queued_scripts)){
			$this->queued_scripts[$name]['queued'] = true;
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
		$queue = apply_filters('proton/assets/scripts', $this->queued_scripts);

		uasort($queue, function($a, $b) {
		    return $a['priority'] - $b['priority'];
		});

		?>
		<script>
			require.config({
				baseUrl: proton.basejs,
				paths: {
<?php
					foreach($queue as $key => $script){
				        $temp_path = $script['path'];
				        echo "\t\t\t\t\t"."$key: '$temp_path',"."\r\n";
			        }
					?>
				},
				shims: {

<?php
					foreach($queue as $key => $script){
				        if(!empty($script['require'])){
					        echo "\t\t\t\t\t"."'$key': { deps: ["."'" . implode ( "', '", $script['require'] ) . "'"."]},"."\r\n";
				        }

			        }
					?>
				}
			});



			if (typeof jQuery === 'function') {
			  define('jquery', function () { return jQuery; });
			}

			require([<?php
					foreach($queue as $key => $script){
				        if($script['queued']){
					        echo "'$key',";
				        }

			        }
				?>]);
			</script>
		<?php
	}


} // End Class


