<?php
// File Security Check
if ( ! defined( 'PROTON_VERSION' ) ) exit;

if(!class_exists('_Proton__Field_upload')):

class _Proton__Field_upload extends _Proton__Field{

	/**
	 * Field Constructor.
	*/
	function __construct(){}

	/**
	 * Field Render Function.
	*/
	public static function make( $options ){
		//Box id
		$ID = 'protonthemes_' . $options['id'];
		$options['name'] = array_key_exists('name', $options) ? $options['name'] : $options['id'];
		if(!array_key_exists('options', $options)){
			$options['options'] = array();
		}
		$output = '';

		$output .= '<fieldset id="' . $options['id'] . '-upload" class="proton-field-upload" data-id="opt-upload" data-type="upload">';
		$output .= '<ul class="proton-container-upload" data-output="'.( array_key_exists('data', $options['options']) ? $options['options']['data'] : 'id' ).'" data-placeholder="' . __( 'Select file', PROTON_SLUG ) . '" data-viewfile="' . __( 'View file', PROTON_SLUG ) . '">';
		if( '' === $options['value']){
		$output .= '<li class="proton-upload-placeholder proton-upload-attachments"><h4>' . __( 'Select file', PROTON_SLUG ) . '</h4></li>';
		}else{
			$image = preg_match( '/(^.*\.jpg|jpeg|png|gif|ico*)/i', $options['value'] );
			if ( $image ) {
				$image = $options['value'];
				if(is_integer($image)){
					$thumb = wp_get_attachment_image_src( $image, 'thumbnail' );
					$full = wp_get_attachment_image_src( $image, 'full' );
					$title = get_the_title($image);
					$name = pathinfo ( $full[0] );
					$output .= '<li class="proton-field-upload-image">';
						$output .= '<img src=" '.$thumb[0].' " alt="" />';
						$output .= '<input data-field="id" type="hidden" name="upload_item[id][]" value="'.$image.'"/>';
						$output .= '<input data-field="img" type="hidden" name="upload_item[img][]" value="'.$full[0].'"/>';
						$output .= '<input data-field="name" type="hidden" name="upload_item[name][]" value="'.$name['filename'].'"/>';
						$output .= '<input data-field="title" type="hidden" name="upload_item[title][]" value="'.$title.'"/>';
					$output .= '</li>';

				}else{
					$image = preg_replace('/(\.gif|\.jpg|\.png)/', '-150x150$1', $options['value']);
					$output .= '<li class="proton-field-upload-image">';
						$output .= '<img src="'.$image.'" alt="" />';
					$output .= '</li>';
				}
			}else{
				$image = $options['value'];
				if(is_integer($image)){
					$full = wp_get_attachment_url( $image);
					$title = get_the_title($image);
					$name = pathinfo ( $full );
					$output .= '<li class="proton-field-upload-image">';
						$output .= '<a href="'.$full.'" target="_blank" rel="external"><h4>' . __( 'View file', PROTON_SLUG ) . '</h4></a>';
						$output .= '<input data-field="title" type="hidden" name="upload_item[title][]" value="'.$title.'"/>';
					$output .= '</li>';
				}else{

				}
			}
		}
        $output .= '</ul>';
        $output .= '<a href="#" onclick="return false;" class="proton-upload-attachments metabutton proton-addimages"><i class="livicon shadowed" data-s="16" data-n="image" data-c="#fff" data-hc="#fff" style="width: 16px; height: 16px;"></i>' . __( 'Add/Edit file', PROTON_SLUG ) . '</a> ';
        $output .= '<a href="#" onclick="return false;" class="proton-upload-attachments metabutton proton-clearimages"><i class="livicon shadowed" data-s="16" data-n="remove-alt" data-c="#fff" data-hc="#fff" style="width: 16px; height: 16px;"></i>' . __( 'Remove file', PROTON_SLUG ) . '</a>';
        $output .= '<input type="hidden" value="' . $options['value'] . '" class="proton-upload-values " name="' . $options['name'] . '" id="' . $ID . '"  />';
        $output .= '</fieldset>';

        //box main content
        $options['field'] = $output;

		return $options;
	}


	/**
	 * Sanitize Function.
	*/
	public static function sanitize($value){
		return sanitize_text_field($value);
	}

	/**
	 * Enqueue Function.
	*/
	public static function queue(){


		//wp_enqueue_script('proton-fields-upload-js', PROTON_FIELDS_URL.'upload/field-upload.js', array('jquery'), PROTON_VERSION, true);
		proton_enqueue_script('proton-fields-upload-js', PROTON_FIELDS_URL.'upload/js/field-upload.min.js', PROTON_VERSION, true);
		wp_enqueue_media();

	}

}

endif;