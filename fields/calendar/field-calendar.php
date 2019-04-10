<?php
// File Security Check
if ( ! defined( 'PROTON_VERSION' ) ) exit;

if(!class_exists('_Proton__Field_calendar')):

class _Proton__Field_calendar extends _Proton__Field{

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
        $options['field'] = '<input placeholder="'.( array_key_exists('placeholder', $options) ? $options['placeholder'] : '' ) .'" class="proton_input_calendar" type="text" name="'.$options['name'].'" id="'.esc_attr( $ID ).'" value="'.esc_attr( $options['value'] ).'">';


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

		wp_enqueue_script( 'jquery-ui-datepicker' );
		proton_enqueue_script('proton-calendar-js', PROTON_FIELDS_URL.'calendar/js/field-calendar.min.js', PROTON_VERSION, true, 2);

		wp_register_style('proton-calendar-css',PROTON_FIELDS_URL.'calendar/css/field-calendar.css');
		wp_enqueue_style( 'jquery-ui-datepicker' );
		wp_enqueue_style( 'proton-calendar-css' );

	}

}
endif;