<?php
namespace Proton\Helpers;

class Twig{

	/**
	* Holds the instance of this class.
	*/
	private static $instance;

	/**
	 * Template Constructor.
	*/
	public function __construct(){
		add_filter( 'timber/twig', array( $this, 'register_twig' ));
	}

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
	 * register_twig function.
	 *
	 * @access public
	 * @param mixed $twig
	 * @return void
	 */

	public function register_twig( $twig ){
		$twig->addFunction( new \Timber\Twig_Function( '_Proton__image_static', array($this, 'twig_thumbnail') ) );
		$twig->addFunction( new \Timber\Twig_Function( '_Proton__image_src', array($this, 'twig_thumbnail_src') ) );
		$twig->addFunction( new \Timber\Twig_Function( '_Proton__image_responsive', array($this, 'twig_flexthumbnail') ) );
		return $twig;
	}


	/**
	 * twig_thumbnail function.
	 *
	 * @access public
	 * @static
	 * @param mixed $source
	 * @param mixed $size
	 * @param mixed $alt
	 * @param mixed $title
	 * @return void
	 */
	public static function twig_thumbnail($source, $size, $alt, $title, $class = 'proton_thumbnail') {
		$args = array (
				 		'source' => $source,
				 		'size' => $size,
				 		'alt' => $alt,
				 		'title' => $title,
				 		'echo' => 0
				 	);
		 return Image::thumbnail($args);
	}

	public static function twig_thumbnail_src($source, $size) {
		$args = array (
				 		'source' => $source,
				 		'size' => $size,
				 		'echo' => 0,
				 		'src' => 1
				 	);
		 return Image::thumbnail($args);
	}

	/**
	* twig_flexthumbnail function.
	*
	* @access public
	* @static
	* @param mixed $name
	* @param mixed $source
	* @param mixed $alt
	* @param mixed $title
	* @return void
	*/
	public static function twig_flexthumbnail($name, $source, $alt, $title) {
		$sizes = apply_filters( 'proton/images/', array());
		$args = array (
				'source' => $source,
				'sizes' => $sizes[ $name ],
				'alt' => $alt,
				'title' => $title,
				'echo' => 0
		);
		return Image::flexthumbnail($args);
	}



} // End Class
