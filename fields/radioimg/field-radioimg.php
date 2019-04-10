<?php
// File Security Check
if ( ! defined( 'PROTON_VERSION' ) ) exit;

if(!class_exists('_Proton__Field_radioimg')):

class _Proton__Field_radioimg extends _Proton__Field{

	/**
	 * Field Constructor.
	*/
	function __construct( ){}

	/**
	 * Field Render Function.
	*/
	public static function make( $options ){
		//Box id
		$ID = 'protonthemes_' . $options['id'];
		$options['name'] = array_key_exists('name', $options) ? $options['name'] : $options['id'];

		$options['attributes'] = '';

        /* = '<input class="proton_input_text '. ( $options['template'] === 'seo' ? 'words-count ' : ''  ) .'" type="'.$options['type'].'" value="'.esc_attr( $options['value'] ).'" name="'.$options['name'].'" placeholder="'.( array_key_exists('placeholder', $options) ? $options['placeholder'] : '' ) .'" id="'.esc_attr( $ID ).'" />';

		return $options;*/



		$i = 0;
		$select_value = '';
		$output = '';

		foreach ( $options['options']['choices'] as $key => $option ) {
			 $i++;

			 $checked = '';
			 $selected = '';
			 if( array_key_exists('value', $options) && $options['value'] !== '' ) {
			 	if ( $options['value'] == $key ) { $checked = ' checked'; $selected = 'proton-field-radioimg-selected'; }
			 } else {
			 	if ( isset( $options['default'] ) && $key === $options['default'] ) { $checked = ' checked'; }
				elseif ( $i == 1 ) { $checked = ' checked'; $selected = 'proton-field-radioimg-selected'; }
				else { $checked = ''; }
			 }

				/*$layout .= '<div class="proton-field-radioimg-label">';
				$layout .= '<input type="radio" id="' . $ID . $i . '" class="proton-field-radioimg-radio '.$this->field['class'] .'" value="' . esc_attr($key) . '" name="' . $options['id'] . '" ' . $checked . ' />';
				$layout .= '&nbsp;' . esc_html($key) . '<div class="proton_spacer"></div></div>';
				$layout .= '<img src="' . esc_url( $option ) . '" alt="" class="proton-field-radioimg-img '. $selected .'" onClick="document.getElementById(\''. esc_js( $ID . $i ) . '\').checked = true;" />';
				*/
				if($options['value'] == $key) { $checked = ' checked'; } else { $checked=''; }

	            //box main content
				$output .= '<div class="proton-radioimg-group"><input type="radio" '.$checked.' value="' . $key . '" class="proton-radioimg-radio"  name="'. esc_attr( $options['name'] ) .'" id="' . $ID . '_' . $key . '" />';
	            $output .= '<label for="' . $ID . '_' . $key . '">';
	            $output .= '<img src="' . esc_url( $option ) . '" alt="" class="proton-radioimg-img '. $selected .'" />';
	            $output .= '</label>';
	            $output .= '</div>';
			}


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
		proton_enqueue_script('proton-fields-images-js', PROTON_FIELDS_URL.'radioimg/js/field-radioimg.min.js', PROTON_VERSION, true, 2);
	}

}
endif;