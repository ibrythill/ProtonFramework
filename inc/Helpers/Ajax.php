<?php
namespace Proton\Helpers;

class Ajax{

	/**
	* Holds the instance of this class.
	*/
	private static $instance;

	/**
	 * Template Constructor.
	*/
	private function __construct(){
		add_action( 'wp_ajax_Proton__ajax_attachment_id', array( $this, 'proton_ajax_attachment_id' ) );
		add_action( 'wp_ajax_Proton__ajax_attachment_url', array( $this, 'proton_ajax_attachment_url' ) );

		add_action( 'wp_ajax_Proton__ajax_oembed_preview', array( $this, 'proton_ajax_oembed_preview' ) );
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

	public function proton_ajax_attachment_id() {
		check_ajax_referer( "PROTON", 'security' );
		$url = sanitize_text_field( $_POST['pimurl'] );
		wp_die(self::get_attachment_id_from_url($url));
	}

	public function proton_ajax_attachment_url() {
		check_ajax_referer( "PROTON", 'security' );
		$id = sanitize_text_field( $_POST['pimid'] );
		$size = isset( $_POST['pimsize'] ) ? sanitize_text_field( $_POST['pimsize'] ) : 'full';

		if( !is_numeric($id) ){
			$id = self::get_attachment_id_from_url( $id );
		};

		$source = wp_get_attachment_url( $id );
		if (preg_match('/(\.jpg|\.jpeg|\.gif|\.png|\.bmp)$/', $source)) {
		    $source = wp_get_attachment_image_src( $id, $size );
			$source = $source[0];
			wp_die($source);
		}
		//$source = $source[0];
		wp_die($source);
	}

	public function proton_ajax_oembed_preview() {
        $data = $_POST['media_url'];
	    //$data = json_encode($data);
        try {
            echo wp_oembed_get($url, array(
	            'width' => 800,
	            'height' => 500
	        ));
        } catch(Exception $e) {
            echo $e->getMessage();
        }
        die();
    }



} // End Class
