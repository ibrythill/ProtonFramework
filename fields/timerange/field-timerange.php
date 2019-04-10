<?php
// File Security Check
if ( ! defined( 'PROTON_VERSION' ) ) exit;

if(!class_exists('_Proton__Field_timerange')):

class _Proton__Field_timerange extends _Proton__Field{

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
		$field_attr = '';
		$field_attr_sep = ' ';
		$defaults = array(
			"step" => 10, //Set sliders step. Always > 0. Could be fractional.

			"hours" => 24, //Set minimum diapason between sliders. Only in "double" type
		);
		if(array_key_exists("options", $options)){
			foreach ( $defaults as $key => $default ) {
				if(array_key_exists($key, $options["options"])){
					$data_type = str_replace("_","-",$key);
					$field_attr .= 'data-'.$data_type.'="'.$options["options"][$key].'"'.$field_attr_sep;
				}
			}
		}

		 $field_attr = trim($field_attr);

    	        $options['field'] = '<input class="proton_input_timerange" '.$field_attr.' type="text" value="'.esc_attr( $options['value'] ).'" name="'.$options['name'].'" id="'.esc_attr( $ID ).'" />';

		return $options;
	}


	/**
	 * Sanitize Function.
	*/
	public static function sanitize($value){
		$value = sanitize_text_field($value);
		$values = explode('-', $value);
		$value = @date('H:i', mktime(0, $values[0])).' - '.@date('H:i', mktime(0, $values[1]));
		//$date->format('Y-m-d H:i:s');
		return $value;//sanitize_text_field($value);
	}

	/**
	 * Enqueue Function.
	*/
	public static function queue(){

		//wp_enqueue_script('ionslider', PROTON_FIELDS_URL.'slider/js/ion.rangeSlider.js', array('jquery'), PROTON_VERSION, true);
		//wp_enqueue_script('proton-fields-timerange-js', PROTON_FIELDS_URL.'timerange/js/field-timerange.js', array('jquery'), PROTON_VERSION, true);

		proton_enqueue_script('proton-ionslider', PROTON_FIELDS_URL.'slider/js/ion.rangeSlider.min.js', PROTON_VERSION, true);
		proton_enqueue_script('proton-fields-timerange-js', PROTON_FIELDS_URL.'timerange/js/field-timerange.min.js', PROTON_VERSION, true, 2);

		wp_enqueue_style( 'ionslider', PROTON_FIELDS_URL.'slider/css/ion.rangeSlider.css', false, 1 );
		wp_enqueue_style( 'ionsliderskin', PROTON_FIELDS_URL.'slider/css/ion.rangeSlider.skinModern.css', false, 1 );

	}

}

endif;