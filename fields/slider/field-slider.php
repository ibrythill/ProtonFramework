<?php
// File Security Check
if ( ! defined( 'PROTON_VERSION' ) ) exit;

if(!class_exists('_Proton__Field_slider')):

class _Proton__Field_slider extends _Proton__Field{

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
			"type" => "single", // or "double"

			"min" => 10, //Set slider minimum value
			"max" => 100, //Set slider maximum value
			"from" => 23, //Set start position for left handle (or for single handle)
			"to" => 100, //Set start position for right handle
			"step" => 2, //Set sliders step. Always > 0. Could be fractional.

			"min_interval" => "", //Set minimum diapason between sliders. Only in "double" type
			"max_interval" => "", //Set maximum diapason between sliders. Only in "double" type
			"drag_interval" => "false", //Allow user to drag whole range. Only in "double" type (beta)

			"values" => "[]", //array, Set up your own array of possible slider values. They could be numbers or strings. If the values array is set up, min, max and step param, are no longer can be changed.

			"from_fixed" => "false", //Fix position of left (or single) handle.
			"from_min" => "null", //Set minimum limit for left handle.
			"from_max" => "null", //Set the maximum limit for left handle
	        "from_shadow" => "false", //Highlight the limits for left handle

	        "to_fixed" => "false", //Fix position of right handle.
	        "to_min" => "null", //Set the minimum limit for right handle
	        "to_max" => "null", //Set the maximum limit for right handle
	        "to_shadow" => "false", //Highlight the limits for right handle

	        "prettify_enabled" => "true", //Improve readability of long numbers. 10000000 → 10 000 000
	        "prettify_separator" => " ", //Set up your own separator for long numbers. 10 000, 10.000, 10-000 и т.п.

	        "force_edges" => "false", //Slider will be always inside it's container.

	        "keyboard" => "false", //Activates keyboard controls. Move left: ←, ↓, A, S. Move right: →, ↑, W, D.
	        "keyboard_step" => 5, //Movement step, than controling from keyboard. In percents.

	        "grid" => "false", //Enables grid of values.
	        "grid_margin" => "true", //Set left and right grid borders.
	        "grid_num" => 4, //Number of grid units.
	        "grid_snap" => "false", //Snap grid to sliders step (step param). If activated, grid_num will not be used.

	        "hide_min_max" => "false", //Hides min and max labels
	        "hide_from_to" => "false", //Hide from and to lables

	        "prefix" => "", //Set prefix for values. Will be set up right before the number: $100
	        "postfix" => "", //Set postfix for values. Will be set up right after the number: 100k
	        "max_postfix" => "", //Special postfix, used only for maximum value. Will be showed after handle will reach maximum right position. For example 0 — 100+
	        "decorate_both" => "true", //Used for "double" type and only if prefix or postfix was set up. Determine how to decorate close values. For example: $10k — $100k or $10 — 100k
	        "values_separator" => " — ", //Set your own separator for close values. Used for "double" type. Default: 10 — 100. Or you may set: 10 to 100, 10 + 100, 10 → 100 etc.

	        "input_values_separator" => ";", //Separator for double values in input value property.

	        "disable" => "false" //Locks slider and makes it inactive.
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

    	        $options['field'] = '<input class="proton_input_slider" '.$field_attr.' type="text" value="'.esc_attr( $options['value'] ).'" name="'.$options['name'].'" id="'.esc_attr( $ID ).'" />';

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

		//wp_enqueue_script('ionslider', PROTON_FIELDS_URL.'slider/js/ion.rangeSlider.js', array('jquery'), PROTON_VERSION, true);
		//wp_enqueue_script('proton-fields-slider-js', PROTON_FIELDS_URL.'slider/js/field-slider.js', array('jquery'), PROTON_VERSION, true);
		proton_enqueue_script('proton-ionslider', PROTON_FIELDS_URL.'slider/js/ion.rangeSlider.min.js', PROTON_VERSION, true);
		proton_enqueue_script('proton-fields-slider-js', PROTON_FIELDS_URL.'slider/js/field-slider.min.js', PROTON_VERSION, true, 2);

		wp_enqueue_style( 'ionslider', PROTON_FIELDS_URL.'slider/css/ion.rangeSlider.css', false, 1 );
		wp_enqueue_style( 'ionsliderskin', PROTON_FIELDS_URL.'slider/css/ion.rangeSlider.skinModern.css', false, 1 );

	}

}

endif;