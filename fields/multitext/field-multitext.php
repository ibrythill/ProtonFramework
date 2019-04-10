<?php
// File Security Check
if ( ! defined( 'PROTON_VERSION' ) ) exit;

if(!class_exists('_Proton__Field_multitext')):

class _Proton__Field_multitext extends _Proton__Field{

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
		$output ='';



	        $output .= '<ul id="'.esc_attr( $ID ).'-ul">';

	        if(array_key_exists('value', $options) && is_array($options['value'])){
				foreach($options['value'] as $k => $value){
					if($value != ''){

						$output .= '<li><input type="text" id="'.esc_attr( $ID ).'-'.$k.'" name="'.$options['id'].'[]" value="'.esc_attr($value).'" class="proton_input_multitext" /> <a href="#button" class="proton-multi-text-remove metabutton"><i class="livicon shadowed" data-s="16" data-n="remove-alt" data-c="#fff" data-hc="#fff" style="width: 16px; height: 16px;"></i>'.__('Remove', PROTON_SLUG).'</a></li>';

					}//if

				}//foreach
			}

			$output .= '<li style="display:none;"><input type="text" id="'.esc_attr( $ID ).'" name="'.$options['name'].'" value="" class="proton_input_multitext" /> <a href="#button" class="proton-multi-text-remove metabutton"><i class="livicon shadowed" data-s="16" data-n="remove-alt" data-c="#fff" data-hc="#fff" style="width: 16px; height: 16px;"></i>'.__('Remove', PROTON_SLUG).'</a></li>';

			$output .= '</ul>';

			$output .= '<a href="#button" class="proton-multi-text-add metabutton" rel-id="'.esc_attr( $ID ).'-ul" rel-name="'.$options['id'].'[]" ><i class="livicon shadowed" data-s="16" data-n="plus-alt" data-c="#fff" data-hc="#fff" style="width: 16px; height: 16px;"></i>'.__('Add More', PROTON_SLUG).'</a><br/>';

	        $options['field'] = $output;

		return $options;
	}


	/**
	 * Sanitize Function.
	*/
	public static function sanitize($value){
		return $value;//_Proton__Opt::sanitize_array($value);
	}

	/**
	 * Enqueue Function.
	*/
	public static function queue(){
		proton_enqueue_script('proton-fields-multitext-js', PROTON_FIELDS_URL.'multitext/js/field-multitext.min.js', PROTON_VERSION, true, 2);

	}

}

endif;