<?php
namespace Proton\Media;

class Image{

	/**
	* Holds the instance of this class.
	*/
	private static $instance;

	/**
	 * Array of IMAGE SIZES.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    array
	 */
	public $sizes = array();

	/**
	 * Field Constructor.
	*/
	private function __construct(){
		add_filter( 'image_downsize', array(&$this,'gambit_otf_regen_thumbs_media_downsize'), 10, 3 );
		add_filter( 'image_resize_dimensions', array(&$this, 'image_resize_dimensions'), 9, 6 );

		$this->generate_sizes();
	}


	/**
	 * Returns the instance.
	 *
	 * @since  1.1.0
	 * @access public
	 * @return object
	 */
	public static function get_instance() {

		if ( !self::$instance )
			self::$instance = new self;

		return self::$instance;
	}

	/**
	 * generate_sizes function.
	 *
	 * @access public
	 * @return void
	 */
	public function generate_sizes() {
		$this->sizes = apply_filters( 'proton/images/', array());

		if(empty($this->sizes)){return;}
		foreach( $this->sizes as $key => $size){
			foreach( $size as $image){
				add_image_size( $key.'_'.$image[0].'x'.$image[1], $image[0], $image[1] );
			}
		}
	}


	/**
	 * thumbnail function.
	 *
	 * @access public
	 * @static
	 * @param mixed $args
	 * @return void
	 */
	public static function thumbnail($args) {
			extract( wp_parse_args( $args,
				array (
			 		'source' => '',
			 		'size' => 'full',
			 		'class' => 'proton_thumbnail',
			 		'alt' => __('Post thumbnail', PROTON_SLUG),
			 		'attr' => '',
			 		'rel' => '',
			 		'title' => __('Post thumbnail', PROTON_SLUG),
			 		'echo' => 1,
			 		'src' => 0
			 	) )
			);

			if(empty($source)){return;}

			if( is_numeric($source) ){
				//$source = wp_get_attachment_image_src( $source, 'full' );
				//$source = $source[0];
				$attachment_id = $source;
			}else{
				$attachment_id = self::get_attachment_id_from_url( $source );
			};
			if(!is_array($size) && preg_match("/\d+x\d+/", $size)){
				$size = explode('x', $size);
			}
			  $image_attributes = wp_get_attachment_image_src( $attachment_id, $size ); // returns an array
			  $path = $image_attributes[0];
//var_dump($size);

			  $separator = ' ';

			  $attributes  = 'class="'.$class.'"' . $separator;
			  $attributes .= 'alt="'.$alt.'"' . $separator;
			  $attributes .= ('' !== $rel ? 'rel="'.$rel.'"' . $separator : '');
			  $attributes .= 'title="'.$title.'"' . $separator;
			  $attributes .= $attr;

			  $output = '<img src="' . $path . '" '.trim( $attributes, $separator ).' />';

			if($src){$output = $path;}

			if (!$echo){
				return $output;
			}

			echo $output;
	 }


	 /**
	  * flexthumbnail function.
	  *
	  * @access public
	  * @static
	  * @param mixed $args
	  * @return void
	  */
	 public static function flexthumbnail($args) {
			$theme_support_sizes = get_theme_support( 'proton-theme-fleximg' );
			if(is_array($theme_support_sizes[0])){
				$theme_support_sizes = $theme_support_sizes[0];
			}else{
				$theme_support_sizes = array();
			}
			$defined_sizes = apply_filters( 'proton/images/', $fleximgs);

			extract( wp_parse_args( $args,
					array (
				 		'name' => '',
				 		'sizes' => array(),
				 		'source' => '',
				 		'class' => 'pro_thumbnail',
				 		'alt' => __('Post thumbnail', PROTON_SLUG),
				 		'rel' => '',
				 		'title' => __('Post thumbnail', PROTON_SLUG),
				 		'echo' => 1
				 	) )
				);

			if(empty($source)){return;}
			if(empty($name) && empty($sizes)){return;}


			if(is_array($sizes)){
				$flex_img_sizes = $sizes;
			}elseif(!empty($name)){
				$flex_img_sizes = $defined_sizes[$name];
			}

			if ( !is_array( $flex_img_sizes ) ){return;}


			$returnimg = '<picture>';
			$returnimg .= '	<!--[if IE 9]><video style="display: none;"><![endif]-->';
			foreach($flex_img_sizes as $key => $img_size){
					if($key == 0){
						continue;
					}
						$params = array(
							'source' => $source,
							'size' => array($img_size[0], $img_size[1]),
							'echo' => 0,
							'src' => 1
						);


					  $params_retina = $params;
					  $params_retina['size'] = array(($img_size[0] * 2), ($img_size[1] * 2));

					$returnimg .= '	<source data-srcset="'.self::thumbnail($params).' 1x, '.self::thumbnail($params_retina).' 2x" media="(min-width: '.$img_size[2].'px)">';


			}

			$params = array(
				'source' => $source,
				'size' => array($flex_img_sizes[0][0], $flex_img_sizes[0][1]),
				'echo' => 0,
				'src' => 1
			);

			$params_retina = $params;
			$params_retina['size'] = array(($flex_img_sizes[0][0] * 2), ($flex_img_sizes[0][1] * 2));

			$returnimg .= '	<!--[if IE 9]></video><![endif]-->';
			$returnimg .= '	<img data-src="'.self::thumbnail($params).'" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" class="lazyload" alt="'.$alt.'" >';
			$returnimg .= '</picture>';

			if (!$echo){
					return $returnimg;
				}

				echo $returnimg;

		}



		/**
		 * Return an ID of an attachment by searching the database with the file URL.
		 *
		 * First checks to see if the $url is pointing to a file that exists in
		 * the wp-content directory. If so, then we search the database for a
		 * partial match consisting of the remaining path AFTER the wp-content
		 * directory. Finally, if a match is found the attachment ID will be
		 * returned.
		 *
		 * @param string $url The URL of the image (ex: http://mysite.com/wp-content/uploads/2013/05/test-image.jpg)
		 *
		 * @return int|null $attachment Returns an attachment ID, or null if no attachment is found
		 */
		public static function get_attachment_id_from_url( $attachment_url = '' ) {

			global $wpdb;
			$attachment_id = false;

			// If there is no url, return.
			if ( '' == $attachment_url )
				return;

			// Get the upload directory paths
			$upload_dir_paths = wp_upload_dir();

			// Make sure the upload path base directory exists in the attachment URL, to verify that we're working with a media library image
			if ( false !== strpos( $attachment_url, $upload_dir_paths['baseurl'] ) ) {

				// If this is the URL of an auto-generated thumbnail, get the URL of the original image
				$attachment_url = preg_replace( '/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $attachment_url );

				// Remove the upload path base directory from the attachment URL
				$attachment_url = str_replace( $upload_dir_paths['baseurl'] . '/', '', $attachment_url );

				// Finally, run a custom database query to get the attachment ID from the modified attachment URL
				$attachment_id = $wpdb->get_var( $wpdb->prepare( "SELECT wposts.ID FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta WHERE wposts.ID = wpostmeta.post_id AND wpostmeta.meta_key = '_wp_attached_file' AND wpostmeta.meta_value RLIKE '%s' AND wposts.post_type = 'attachment'", $attachment_url ) );

			}

			return $attachment_id;
		}




		/**
		 * Simple but effectively resizes images on the fly. Doesn't upsize, just downsizes like how WordPress likes it.
		 * If the image already exists, it's served. If not, the image is resized to the specified size, saved for
		 * future use, then served.
		 *
		 * @author	Benjamin Intal - Gambit Technologies Inc
		 * @see https://wordpress.stackexchange.com/questions/53344/how-to-generate-thumbnails-when-needed-only/124790#124790
		 * @see http://codex.wordpress.org/Function_Reference/get_intermediate_image_sizes
		 */

		/**
		 * The downsizer. This only does something if the existing image size doesn't exist yet.
		 *
		 * @param	$out boolean false
		 * @param	$id int Attachment ID
		 * @param	$size mixed The size name, or an array containing the width & height
		 * @return	mixed False if the custom downsize failed, or an array of the image if successful
		 */
		function gambit_otf_regen_thumbs_media_downsize( $out, $id, $size ) {
			// Gather all the different image sizes of WP (thumbnail, medium, large) and,
			// all the theme/plugin-introduced sizes.
			global $_gambit_otf_regen_thumbs_all_image_sizes;
			if ( ! isset( $_gambit_otf_regen_thumbs_all_image_sizes ) ) {
				global $_wp_additional_image_sizes;

				$_gambit_otf_regen_thumbs_all_image_sizes = array();
				$interimSizes = get_intermediate_image_sizes();

				foreach ( $interimSizes as $sizeName ) {
					if ( in_array( $sizeName, array( 'thumbnail', 'medium', 'large' ) ) ) {
						$_gambit_otf_regen_thumbs_all_image_sizes[ $sizeName ]['width'] = get_option( $sizeName . '_size_w' );
						$_gambit_otf_regen_thumbs_all_image_sizes[ $sizeName ]['height'] = get_option( $sizeName . '_size_h' );
						$_gambit_otf_regen_thumbs_all_image_sizes[ $sizeName ]['crop'] = (bool) get_option( $sizeName . '_crop' );
					} elseif ( isset( $_wp_additional_image_sizes[ $sizeName ] ) ) {
						$_gambit_otf_regen_thumbs_all_image_sizes[ $sizeName ] = $_wp_additional_image_sizes[ $sizeName ];
					}
				}
			}

			// This now contains all the data that we have for all the image sizes
			$allSizes = $_gambit_otf_regen_thumbs_all_image_sizes;

			// If image size exists let WP serve it like normally
			$imagedata = wp_get_attachment_metadata( $id );

			// Image attachment doesn't exist
			if ( ! is_array( $imagedata ) ) {
				return false;
			}

			// If the size given is a string / a name of a size
			if ( is_string( $size ) ) {

				// If WP doesn't know about the image size name, then we can't really do any resizing of our own
				if ( empty( $allSizes[ $size ] ) ) {
					return false;
				}

				// If the size has already been previously created, use it
				if ( ! empty( $imagedata['sizes'][ $size ] ) && ! empty( $allSizes[ $size ] ) ) {

					// But only if the size remained the same
					if ( $allSizes[ $size ]['width'] == $imagedata['sizes'][ $size ]['width']
					&& $allSizes[ $size ]['height'] == $imagedata['sizes'][ $size ]['height'] ) {
						return false;
					}

					// Or if the size is different and we found out before that the size really was different
					if ( ! empty( $imagedata['sizes'][ $size ][ 'width_query' ] )
					&& ! empty( $imagedata['sizes'][ $size ]['height_query'] ) ) {
						if ( $imagedata['sizes'][ $size ]['width_query'] == $allSizes[ $size ]['width']
						&& $imagedata['sizes'][ $size ]['height_query'] == $allSizes[ $size ]['height'] ) {
							return false;
						}
					}

				}

				// Resize the image
				$resized = image_make_intermediate_size(
					get_attached_file( $id ),
					$allSizes[ $size ]['width'],
					$allSizes[ $size ]['height'],
					$allSizes[ $size ]['crop']
				);

				// Resize somehow failed
				if ( ! $resized ) {
					return false;
				}

				// Save the new size in WP
				$imagedata['sizes'][ $size ] = $resized;

				// Save some additional info so that we'll know next time whether we've resized this before
				$imagedata['sizes'][ $size ]['width_query'] = $allSizes[ $size ]['width'];
				$imagedata['sizes'][ $size ]['height_query'] = $allSizes[ $size ]['height'];

				wp_update_attachment_metadata( $id, $imagedata );

				// Serve the resized image
				$att_url = wp_get_attachment_url( $id );
				return array( dirname( $att_url ) . '/' . $resized['file'], $resized['width'], $resized['height'], true );


			// If the size given is a custom array size
			} else if ( is_array( $size ) ) {
				$imagePath = get_attached_file( $id );
				$crop = array_key_exists(2, $size) ? $size[2] : true;
				$new_width = $size[0];
				$new_height = $size[1];
				// If crop is false, calculate new image dimensions
				if (!$crop) {
					if ( class_exists( 'Jetpack' ) && Jetpack::is_module_active( 'photon' ) ) {
						add_filter( 'jetpack_photon_override_image_downsize', '__return_true' );
						$trueData = wp_get_attachment_image_src($id, 'large');
						remove_filter( 'jetpack_photon_override_image_downsize', '__return_true' );
					}
					else {
						$trueData = wp_get_attachment_image_src($id, 'large');
					}
					if ($trueData[1] > $trueData[2]) {
						// Width > height
						$ratio = $trueData[1] / $size[0];
						$new_height = round($trueData[2] / $ratio);
						$new_width = $size[0];
					}
					else {
						// Height > width
						$ratio = $trueData[2] / $size[1];
						$new_height = $size[1];
						$new_width = round($trueData[1] / $ratio);
					}
				}
				// This would be the path of our resized image if the dimensions existed
				$imageExt = pathinfo( $imagePath, PATHINFO_EXTENSION );
				$imagePath = preg_replace( '/^(.*)\.' . $imageExt . '$/', sprintf( '$1-%sx%s.%s', $new_width, $new_height, $imageExt ) , $imagePath );

				$att_url = wp_get_attachment_url( $id );

				// If it already exists, serve it
				if ( file_exists( $imagePath ) ) {
					return array( dirname( $att_url ) . '/' . basename( $imagePath ), $new_width, $new_height, $crop );
				}
				// If not, resize the image...
				$resized = image_make_intermediate_size(
					get_attached_file( $id ),
					$size[0],
					$size[1],
					$crop
				);

				// Get attachment meta so we can add new size
				$imagedata = wp_get_attachment_metadata( $id );
				// Save the new size in WP so that it can also perform actions on it
				$imagedata['sizes'][ $size[0] . 'x' . $size[1] ] = $resized;
				wp_update_attachment_metadata( $id, $imagedata );

				// Resize somehow failed
				if ( ! $resized ) {
					return false;
				}

				// Then serve it
				return array( dirname( $att_url ) . '/' . $resized['file'], $resized['width'], $resized['height'], $crop );
			}

			return false;
		}

		public static function image_resize_dimensions($default, $orig_w, $orig_h, $new_w, $new_h, $crop)
		{
			if(!$crop){
				return null; // let the wordpress default function handle this
				}

			$size_ratio = max($new_w / $orig_w, $new_h / $orig_h);

			$crop_w = round($new_w / $size_ratio);
			$crop_h = round($new_h / $size_ratio);

			$s_x = floor( ($orig_w - $crop_w) / 2 );
			$s_y = floor( ($orig_h - $crop_h) / 2 );
			if(is_array($crop)) {
				//Handles left, right and center (no change)
				if($crop[ 0 ] === 'left') {
					$s_x = 0;
				} else if($crop[ 0 ] === 'right') {
					$s_x = $orig_w - $crop_w;
				}
				//Handles top, bottom and center (no change)
				if($crop[ 1 ] === 'top') {
					$s_y = 0;
				} else if($crop[ 1 ] === 'bottom') {
					$s_y = $orig_h - $crop_h;
				}
			}

			return array( 0, 0, (int) $s_x, (int) $s_y, (int) $new_w, (int) $new_h, (int) $crop_w, (int) $crop_h );
		}


} //END Class
