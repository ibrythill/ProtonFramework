<?php
// File Security Check
if ( ! defined( 'PROTON_VERSION' ) ) exit;

if(!class_exists('_Proton__Field_textarea')):

class _Proton__Field_textarea extends _Proton__Field{

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

		//box seo addons
		$add_counter = '';
		if($options['template'] === 'seo'){
			$options['classes'] .= 'words-count ';
			$add_counter = '<br/><span class="counter">0 znaków, 0 słów</span>';
		}

		//box description
        $options['desc'] = $options['desc'].$add_counter;

        //box main content
        $options['field'] = '<textarea class="proton_input_textarea" placeholder="'.( array_key_exists('placeholder', $options) ? $options['placeholder'] : '' ) .'" name="'.$options['name'].'" id="'.esc_attr( $ID ).'" >' . esc_textarea(stripslashes($options['value'])) . '</textarea>';



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
		wp_enqueue_script('proton-fields-text-js', PROTON_FIELDS_URL.'text/field-text.js', array('jquery'), PROTON_VERSION, true);
	}

}

endif;