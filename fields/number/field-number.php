<?php
// File Security Check
if ( ! defined( 'PROTON_VERSION' ) ) exit;

if(!class_exists('_Proton__Field_number')):

class _Proton__Field_number extends _Proton__Field{

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
        $options['field'] = '<input class="proton_input_count" type="' . $options['type'] . '" value="' . esc_attr( $options['value'] ) . '" name="' . $options['name'] . '" id="' . esc_attr( $ID ) . '" />';

		return $options;
	}

	/**
	 * Sanitize Function.
	*/
	public static function sanitize($value){
		return sanitize_text_field($value);
	}

}

endif;