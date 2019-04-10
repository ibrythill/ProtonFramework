<?php
// File Security Check
if ( ! defined( 'PROTON_VERSION' ) ) exit;

if(!class_exists('_Proton__Field_text')):

class _Proton__Field_text extends _Proton__Field{

	/**
	 * Holds the instance of this class.
	 */
	protected static $instance = null;
	/**
	 * Holds the id of field
	 */
	public static $field_name = 'text';

	/**
	 * Field Constructor.
	*/
	protected function __construct( ){
		_Proton__Templates::enqueue(self::$field_name, self::template());
	}

	/**
	 * Field Render Function.
	*/
	public static function make( $options ){
		//Box id
		$ID = 'protonthemes_' . $options['id'];
		$options['name'] = array_key_exists('name', $options) ? $options['name'] : $options['id'];
		$options['fid'] = $ID;

		$options['attributes'] = '';

		//box seo addons
		$add_counter = '';
		if($options['template'] === 'seo'){
			$add_counter = '<br/><span class="counter">0 znaków, 0 słów</span>';
		}

		//box description
        $options['desc'] = $options['desc'].$add_counter;

        //box main content
        $options['field'] = '<input class="proton_input_text '. ( $options['template'] === 'seo' ? 'words-count ' : ''  ) .'" type="'.$options['type'].'" value="'.esc_attr( $options['value'] ).'" name="'.$options['name'].'" placeholder="'.( array_key_exists('placeholder', $options) ? $options['placeholder'] : '' ) .'" id="'.esc_attr( $ID ).'"/>';

		return $options;
	}

	/**
	 * Field underscore template.
	*/
	public static function template(){

		return '<input class="proton_input_text" type="{{ type }}" value="{{ value }}" name="{{ name }}" placeholder="{{ placeholder }}" id="{{ fid }}"/>';
	}




	/**
	 * Enqueue Function.
	*/
	public static function queue(){
		proton_enqueue_script('proton-fields-text-js', PROTON_FIELDS_URL.'text/js/field-text.min.js', PROTON_VERSION, true, 2);
	}

}

endif;