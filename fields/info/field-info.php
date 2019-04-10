<?php
// File Security Check
if ( ! defined( 'PROTON_VERSION' ) ) exit;

if(!class_exists('_Proton__Field_info')):

class _Proton__Field_info extends _Proton__Field{

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


		return $options;
	}
}

endif;