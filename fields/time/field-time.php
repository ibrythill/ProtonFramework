<?php
// File Security Check
if ( ! defined( 'PROTON_VERSION' ) ) exit;

if(!class_exists('_Proton__Field_time')):

class _Proton__Field_time extends _Proton__Field{

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
        $options['field'] = '<div class="proton_input_clockpicker" data-placement="bottom" data-align="left" data-autoclose="true"><input class="proton_input_time" type="text" value="' . esc_attr( $options['value']  ) . '" name="' . $options['name'] . '" id="' . esc_attr( $ID ) . '" /></div>';

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
		//wp_enqueue_script('proton-clockpicker-js', PROTON_FIELDS_URL.'time/jquery-clockpicker.min.js', array('jquery'), PROTON_VERSION, true);
		//wp_enqueue_script('proton-field-time-js', PROTON_FIELDS_URL.'time/field-time.js', array('jquery', 'proton-clockpicker-js'), PROTON_VERSION, true);

		proton_enqueue_script('proton-clockpicker-js', PROTON_FIELDS_URL.'time/js/jquery-clockpicker.min.js', PROTON_VERSION, true);
		proton_enqueue_script('proton-field-time-js', PROTON_FIELDS_URL.'time/js/field-time.min.js', PROTON_VERSION, true, 2);

		wp_enqueue_style( 'proton-fields-time-css', PROTON_FIELDS_URL . 'time/css/jquery-clockpicker.min.css' );
	}
}

endif;