<?php
// File Security Check
if ( ! defined( 'PROTON_VERSION' ) ) exit;

if(!class_exists('_Proton__Field_editor')):

class _Proton__Field_editor extends _Proton__Field{

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
		if(!array_key_exists('options', $options)){
			$options['options'] = array();
		}
		$args = array(
		'textarea_rows' => 10
		 );
		if(array_key_exists('args', $options['options'])){
				$settings = array_merge($args, $options['options']['args']);
			}else{
				$settings = $args;
			}

        ob_start();
		wp_editor( $options['value'], $options['id'],$settings );
		$options['field'] = ob_get_contents();
		ob_end_clean();

		return $options;
	}

	/**
	 * Sanitize Function.
	*/
	public static function sanitize($value){
		return ($value);
	}

	/**
	 * save_handle function.
	 *
	 * @access public
	 * @static
	 * @param mixed $args
	 * @return void
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
			//delete meta
			delete_metadata( $meta_type, $post_id, $field_id, $current_value ); // Deletes opt

		} // End IF Statement
	}

}

endif;
