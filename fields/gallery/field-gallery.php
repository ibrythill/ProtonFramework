<?php
// File Security Check
if ( ! defined( 'PROTON_VERSION' ) ) exit;

if(!class_exists('_Proton__Field_gallery')):

class _Proton__Field_gallery extends _Proton__Field{

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
		$output = '';

		$output .= '<fieldset id="' . $options['id'] . '-gallery" class="proton-field-gallery" data-id="opt-gallery" data-type="gallery">';
		$output .= '<ul class="proton-container-gallery gallery_sort" data-placeholder="' . __( 'Select images', PROTON_SLUG ) . '">';
		if( '' === $options['value']){
		$output .= '<li class="proton-gallery-placeholder proton-gallery-attachments"><h4>' . __( 'Select images', PROTON_SLUG ) . '</h4></li>';
		}else{
			$values = explode(',', $options['value']);
			foreach ( $values as $image) {
				$thumb = wp_get_attachment_image_src( $image, 'thumbnail' );
				$full = wp_get_attachment_image_src( $image, 'full' );
				$title = get_the_title($image);
				$name = pathinfo ( $full[0] );
				$output .= '<li class="proton-field-gallery-image">';
					$output .= '<img src=" '.$thumb[0].' " alt="" />';
					$output .= '<input data-field="id" type="hidden" name="gallery_item[id][]" value="'.$image.'"/>';
					$output .= '<input data-field="img" type="hidden" name="gallery_item[img][]" value="'.$full[0].'"/>';
					$output .= '<input data-field="name" type="hidden" name="gallery_item[name][]" value="'.$name['filename'].'"/>';
					$output .= '<input data-field="title" type="hidden" name="gallery_item[title][]" value="'.$title.'"/>';
				$output .= '</li>';
			}
		}
        $output .= '</ul>';
        $output .= '<a href="#" onclick="return false;" class="proton-gallery-attachments metabutton proton-addimages"><i class="livicon shadowed" data-s="16" data-n="image" data-c="#fff" data-hc="#fff" style="width: 16px; height: 16px;"></i>' . __( 'Add/Edit images', PROTON_SLUG ) . '</a> ';
        $output .= '<a href="#" onclick="return false;" class="proton-gallery-attachments metabutton proton-clearimages"><i class="livicon shadowed" data-s="16" data-n="remove-alt" data-c="#fff" data-hc="#fff" style="width: 16px; height: 16px;"></i>' . __( 'Clear images', PROTON_SLUG ) . '</a>';
        $output .= '<input type="hidden" value="' . $options['value'] . '" class="proton-gallery-values " name="' . $options['name'] . '" id="' . $ID . '"  />';
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


		//wp_enqueue_script('proton-fields-gallery-js', PROTON_FIELDS_URL.'gallery/field-gallery.js', array('jquery'), PROTON_VERSION, true);
		proton_enqueue_script('proton-fields-gallery-js', PROTON_FIELDS_URL.'gallery/js/field-gallery.min.js', PROTON_VERSION, true);
		wp_enqueue_media();

	}

}

endif;