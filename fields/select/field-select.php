<?php
// File Security Check
if ( ! defined( 'PROTON_VERSION' ) ) exit;

if(!class_exists('_Proton__Field_select')):

class _Proton__Field_select extends _Proton__Field{

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

		$sortable = (isset($options['options']['sortable']) && $options['options']['sortable']) ? ' select2-sortable"' : "";
		if (!empty($sortable)) { // Dummy proofing  :P
			$options['options']['multi'] = true;
		}

		if( !empty( $options['options']['data'] ) && empty( $options['options']['choices'] ) ) {
			if (empty($options['options']['args'])) {
				$options['options']['args'] = array();
			}
        	$options['options']['choices'] = parent::get_wordpress_data($options['options']['data'], $options['options']['args']);
        }


		if (!empty($options['options']['choices'])) {
			$multi = (isset($options['options']['multi']) && $options['options']['multi']) ? ' multiple="multiple"' : "";

			$nameBrackets = "";
			if (!empty($multi)) {
				$nameBrackets = "[]";
			}


			$placeholder = (isset($options['placeholder'])) ? esc_attr($options['placeholder']) : __( 'Select an item', PROTON_SLUG );


			if (isset($options['options']['multi']) && $options['options']['multi'] && isset($options['options']['sortable']) && $options['options']['sortable'] && !empty($options['value']) && is_array($options['value'])) {
				$origOption = $options['options']['choices'];
				$options['options']['choices'] = array();
				foreach($options['value'] as $value) {
					$options['options']['choices'][$value] = $origOption[$value];
				}
				if (count($options['options']['choices'])< count($origOption)) {
					foreach ($origOption as $key=>$value) {
						if (!in_array($key, $options['options']['choices'])) {
							$options['options']['choices'][$key] = $value;
						}
					}
				}
			}

        	$sortable = (isset($options['options']['sortable']) && $options['options']['sortable']) ? ' select2-sortable"' : "";

	        $output .= '<select '.$multi.' data-placeholder="' . $placeholder . '" class="proton_input_select '.$sortable.'" id="' . esc_attr( $ID ) . '" name="' . esc_attr( $options['name'] ).$nameBrackets.'" >';
	        $output .= '<option value=""></option>';

	        foreach($options['options']['choices'] as $k => $v){
				if (is_array($options['value'])) {
					$selected = (is_array($options['value']) && in_array($k, $options['value']))?' selected="selected"':'';
				} else {
					$selected = selected($options['value'], $k, false);
				}
				$output .=  '<option value="'.$k.'"'.$selected.'>'.$v.'</option>';
			}//foreach

	        $output .= '</select>';

		}else{
			echo '<strong>'.__('No items of this type were found.', 'proton-framework').'</strong>';
		}
		$options['field'] = $output;
		return $options;
	}


	/**
	 * Sanitize Function.
	*/
	public static function sanitize($value){
		if(is_array($value)){
			return self::sanitize_array($value);
		}else{
			return sanitize_text_field($value);
		}
	}

	/**
	 * Enqueue Function.
	*/
	public static function queue(){
		//wp_enqueue_script('proton-select2', PROTON_FIELDS_URL . 'select/select2/select2.min.js', array('jquery'), PROTON_VERSION, true);
		//wp_enqueue_script('proton-select2-sortable', PROTON_FIELDS_URL.'select/select2.sortable.min.js', array('jquery', 'proton-select2'), PROTON_VERSION, true);
		//wp_enqueue_script('proton-fields-select-js', PROTON_FIELDS_URL.'select/field-select.js', array('jquery', 'proton-select2'), PROTON_VERSION, true);
		proton_enqueue_script('proton-select2', PROTON_FIELDS_URL . 'select/select2/select2.min.js', PROTON_VERSION, true);
		proton_enqueue_script('proton-select2-sortable', PROTON_FIELDS_URL.'select/js/select2.sortable.min.js', PROTON_VERSION, true);
		proton_enqueue_script('proton-fields-select-js', PROTON_FIELDS_URL.'select/js/field-select.min.js', PROTON_VERSION, true, 2);

		wp_enqueue_style( 'proton-select2', PROTON_FIELDS_URL . 'select/select2/select2.css' );
		wp_enqueue_style( 'proton-fields-select-css', PROTON_FIELDS_URL . 'select/css/field-select.css' );
	}

}

endif;