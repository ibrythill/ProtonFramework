<?php
// File Security Check
if ( ! defined( 'PROTON_VERSION' ) ) exit;

if(!class_exists('_Proton__Field_combo')):

class _Proton__Field_combo extends _Proton__Field{

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
		$fieldtypes = array('text', 'textarea', 'calendar', 'upload', 'gallery', 'radio', 'checkbox', 'time', 'number', 'slider', 'iconpicker', 'timerange', 'select', 'color');
		$output ='';
		$amount = 1;


			if(array_key_exists('value', $options) && is_array($options['value'])){
				foreach($options['value'] as $k => $value){
					$amount = count($value);
				}
			}
				for ($k = 1; $k <= $amount; $k++) {
			    $output .= '<div class="proton-multi-fields">';

			        foreach ( $options['fields'] as $opt_field ) {
				        if(!in_array($opt_field['type'], $fieldtypes)){
					        continue;
				        }
				        $opt_field['value'] = '';
				        if(array_key_exists('value', $options) && is_array($options['value'])){
						    if(array_key_exists($opt_field['id'], $options['value'])){
								if(array_key_exists($k-1, $options['value'][$opt_field['id']])){
						        	$value = $options['value'][$opt_field['id']][$k-1];
						        	$opt_field['value'] = '' !== $value ? $value : '';
						        }
					        }
				        }

					    $opt_field['template'] = 'plain';
					    $opt_field['name'] = $options['id'].'['.$opt_field['id'].'][]';
					    $opt_field['id'] = $opt_field['id'].$k;
					    //render field
			        	$output .= _Proton__Opt::render_field ( $opt_field );

				    }
				$output .= "\t". '</div>'."\n";


				}


	        /*


	        $output .= '<ul id="'.esc_attr( $ID ).'-ul">';

	        if(array_key_exists('value', $options) && is_array($options['value'])){
				foreach($options['value'] as $k => $value){
					if($value != ''){

						$output .= '<li><input type="text" id="'.esc_attr( $ID ).'-'.$k.'" name="'.$options['id'].'[]" value="'.esc_attr($value).'" class="proton_input_multitext" /> <a href="#button" class="proton-multi-text-remove metabutton"><i class="livicon shadowed" data-s="16" data-n="remove-alt" data-c="#fff" data-hc="#fff" style="width: 16px; height: 16px;"></i>'.__('Remove', PROTON_SLUG).'</a></li>';

					}//if

				}//foreach
			}*/

	        $options['field'] = $output;

		return $options;
	}


	/**
	 * Sanitize Function.
	*/
	public static function sanitize($value){
		return self::sanitize_array($value);
	}

	public static function save_handle($args){
		parent::save_handle($args);
	}

	/**
	 * Enqueue Function.
	*/
	public static function queue(){
		//wp_enqueue_script('proton-fields-combo-js', PROTON_FIELDS_URL.'combo/field-combo.js', array('jquery'), PROTON_VERSION, true);
		proton_enqueue_script('proton-fields-combo-js', PROTON_FIELDS_URL.'combo/js/field-combo.min.js', PROTON_VERSION, true, 3);
	}

}

endif;
