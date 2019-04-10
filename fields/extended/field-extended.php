<?php
// File Security Check
if ( ! defined( 'PROTON_VERSION' ) ) exit;

if(!class_exists('_Proton__Field_extended')):

class _Proton__Field_extended extends _Proton__Field{

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
		$fieldtypes = array('multi');


        //box main content
        $options['field'] = '<a href="#open" '.(array_key_exists('export', $options) && 'multi' === $options['export'] ? 'data-field-export="multi"' : '').' data-template="template_'.$options['id'].'" class="proton_input_extended_trigger"><span><i class="livicon shadowed" data-s="32" data-n="pen" data-c="#fff" data-hc="#fff" style="width: 32px; height: 32px;"></i>'. __( "Edit settings", PROTON_SLUG ) .'</span></a>';

        if(array_key_exists('export', $options) && 'multi' === $options['export']){
        	foreach ( $options['fields'] as $opt_field ) {
        		$fID = 'protonthemes_' . $opt_field['id'];
        		$opt_field['name'] = array_key_exists('name', $opt_field) ? $opt_field['name'] : $opt_field['id'];
        		$options['field'] .= '<input class="proton_input_extended" type="hidden" value="'.esc_attr( $options['value'] ).'" name="'.$opt_field['name'].'" id="'.esc_attr( $ID ).'"/>';
        	}
		}else{
			$options['field'] .= '<input class="proton_input_extended" type="hidden" value="'.esc_attr( $options['value'] ).'" name="'.$options['name'].'" id="'.esc_attr( $ID ).'"/>';
		}


        $options['field'] .= '<script type="text/html" id="tmpl-template_'.$options['id'].'"><form>';

        //iterate all fields
		    foreach ( $options['fields'] as $opt_field ) {
			    if(in_array($opt_field['type'], $fieldtypes)){
				        continue;
			    }
			    $opt_field['value'] = '';
			    //render field
	        	$options['field'] .= _Proton__Opt::render_field ( $opt_field );
		    }
		$options['field'] .= '</form></script>';

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
		//wp_enqueue_script('proton-serializeobject-js', PROTON_FIELDS_URL.'extended/jquery.serialize-object.min.js', array('jquery'), PROTON_VERSION, true);
		//wp_enqueue_script('proton-fields-extended-js', PROTON_FIELDS_URL.'extended/field-extended.js', array('jquery','proton-serializeobject-js'), PROTON_VERSION, true);
		proton_enqueue_script('proton-serializeobject-js', PROTON_FIELDS_URL.'extended/js/jquery.serialize-object.min.js', PROTON_VERSION, true);
		proton_enqueue_script('proton-fields-extended-js', PROTON_FIELDS_URL.'extended/js/field-extended.min.js', PROTON_VERSION, true, 3);
	}

}

endif;