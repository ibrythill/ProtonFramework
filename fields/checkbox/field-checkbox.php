<?php
// File Security Check
if ( ! defined( 'PROTON_VERSION' ) ) exit;

if(!class_exists('_Proton__Field_checkbox')):

class _Proton__Field_checkbox extends _Proton__Field{

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

		if( 'true' === $options['value'] ) { $checked = ' checked="checked"'; } else { $checked=''; }

		//box main content
        $options['title'] = '<input type="checkbox" '.$checked.' class="proton_input_checkbox" value="true"  id="'.esc_attr( $ID ).'" name="'. esc_attr( $options['name'] ) .'"/><label for="'.esc_attr( $ID ).'"><span class="ui"></span>'.$options['title'].'</label>';

		return $options;
	}

	/**
	 * Sanitize Function.
	*/
	public static function sanitize($value){
		return sanitize_text_field($value);
	}

	/**
	 * Save Function.
	*/
	public static function save_handle($args){

		extract( wp_parse_args( $args,
			array (
				'post_id' => '',
				'meta_type' => 'post',
				'custom_id' => '',
				'opt_field' => array()
			) )
		);

		$field_id = array_key_exists('name', $opt_field) ? $opt_field['name'] : $opt_field['id'];
		$current_value = '';

	    $current_value = get_metadata( $meta_type, $post_id, $field_id, true );


	    // Sanitize the input.
		$posted_value = array_key_exists($custom_id, $_POST) ? $_POST[$custom_id] : '';

		$posted_value = self::sanitize($posted_value);


	    if ( isset( $posted_value ) ) {
			// Otherwise, if no value is set, delete the post meta.
			if($posted_value == "") {
				//delete meta
				delete_metadata( $meta_type, $post_id, $field_id, get_metadata( $meta_type, $post_id, $field_id, true ) );
			}else{
				//update meta
				update_metadata( $meta_type, $post_id, $field_id, $posted_value );
			}// End IF Statement
		} else {
			//update checkbox post meta
			update_metadata( $meta_type, $post_id, $field_id, 'false' );

		}  // End IF Statement
	}

	/**
	 * Enqueue Function.
	*/
	public static function queue(){

		//wp_register_style( 'protoncheckbox', PROTON_FIELDS_URL.'checkbox/field-checkbox.css', false, 1 );
		//wp_enqueue_style('protoncheckbox');

	}

}

endif;
