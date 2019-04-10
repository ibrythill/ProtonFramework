<?php
// File Security Check
if ( ! defined( 'PROTON_VERSION' ) ) exit;

if(!class_exists('_Proton__Field_radio')):

class _Proton__Field_radio extends _Proton__Field{

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

		$choices = $options['options']['choices'];
		$output = '';

        foreach ( $choices as $key => $option ) {
            if($options['value'] == $key) { $checked = ' checked'; } else { $checked=''; }

            //box main content
			$output .= '<div class="radio-group"><input type="radio" '.$checked.' value="' . $key . '" class="proton_input_radio"  name="'. esc_attr( $options['name'] ) .'" id="' . $ID . '_' . $key . '" />';
            $output .= '<label for="' . $ID . '_' . $key . '"><span class="check"></span><span class="box"></span>'.$option.'</label>';
            $output .= '</div><div class="proton_spacer"></div>';

         }
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
		//wp_enqueue_style('proton-fields-radio-css', PROTON_FIELDS_URL.'radio/field-radio.css', false, PROTON_VERSION);
	}

}

endif;