<?php
// File Security Check
if ( ! defined( 'PROTON_VERSION' ) ) exit;

if(!class_exists('_Proton__Field_multi')):

class _Proton__Field_multi extends _Proton__Field{

	public static $fieldtypes = array('text', 'textarea', 'calendar', 'upload', 'gallery', 'radio', 'checkbox', 'time', 'number', 'slider', 'iconpicker', 'timerange', 'select', 'color', 'combo');

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
		$amount = 1;

			$output .= '<div class="proton-multi-wrapper">';
			if(array_key_exists('value', $options) && is_array($options['value'])){
				foreach($options['value'] as $k => $value){
					$amount = count($value);
				}
			}
				for ($k = 1; $k <= $amount; $k++) {
			    $output .= '	<div class="toclone"><div class="proton-multi-fields">';

			        foreach ( $options['fields'] as $opt_field ) {
				        if(!in_array($opt_field['type'], self::$fieldtypes)){
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
					    //$parent_class = new $field_class();
			        	$output .= _Proton__Opt::render_field ( $opt_field );

				    }
				$output .= "\t". '</div>'."\n";
			    //$output .= '        <div class="metabutton clone"><i class="livicon shadowed" data-s="16" data-n="plus-alt" data-c="#fff" data-hc="#fff" style="width: 16px; height: 16px;"></i>'.__('Clone', PROTON_SLUG).'</div>';
			    $output .= '        <div class="metabutton delete"><i class="livicon shadowed" data-s="16" data-n="remove-alt" data-c="#fff" data-hc="#fff" style="width: 16px; height: 16px;"></i>'.__('Remove', PROTON_SLUG).'</div>';
			    $output .= '    </div> ';

				}


			$output .= '</div><div class="clear"></div>';
			$output .= '        <div class="metabutton proton-multi-clone"><i class="livicon shadowed" data-s="16" data-n="plus-alt" data-c="#fff" data-hc="#fff" style="width: 16px; height: 16px;"></i>'.__('Add More', PROTON_SLUG).'</div>';
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

	/**
	 * Enqueue Function.
	*/
	public static function queue(){
		//wp_enqueue_script('proton-fields-cloneya-js', PROTON_FIELDS_URL.'multi/jquery-cloneya.min.js', array('jquery'), PROTON_VERSION, true);
		//wp_enqueue_script('proton-fields-multi-js', PROTON_FIELDS_URL.'multi/field-multi.js', array('jquery', 'proton-fields-cloneya-js'), PROTON_VERSION, true);
		proton_enqueue_script('proton-fields-cloneya-js', PROTON_FIELDS_URL.'multi/js/jquery-cloneya.min.js', PROTON_VERSION, true);
		proton_enqueue_script('proton-fields-multi-js', PROTON_FIELDS_URL.'multi/js/field-multi.min.js', PROTON_VERSION, true, 3);

	}

}

endif;