<?php
// File Security Check
if ( ! defined( 'PROTON_VERSION' ) ) exit;

if(!class_exists('_Proton__Field_color')):

class _Proton__Field_color extends _Proton__Field{

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

		//box main content
        $options['field'] = '<input class="proton_input_color" type="text" value="'.esc_attr( $options['value'] ).'" name="'.$options['name'].'" id="'.esc_attr( $ID ).'"/>';
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

		wp_enqueue_script( 'wp-color-picker' );
		wp_enqueue_style( 'wp-color-picker' );
		proton_enqueue_script('proton-fields-color-js', PROTON_FIELDS_URL.'color/js/field-color.min.js', PROTON_VERSION, true, 2);


	}

}

endif;
