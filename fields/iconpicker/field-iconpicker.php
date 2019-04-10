<?php
// File Security Check
if ( ! defined( 'PROTON_VERSION' ) ) exit;

if(!class_exists('_Proton__Field_iconpicker')):

class _Proton__Field_iconpicker extends _Proton__Field{

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
		$output='<input type="text" value="'.$options['value'].'" class="proton_input_iconpicker" name="'.$options['name'].'" id="'.esc_attr( $ID ).'" />';


		$options['title'] = $output.$options['title'] ;
		if('plain' === $options['template']){
			$options['field'] = $output;
		}
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
		wp_enqueue_style( 'proton-fonticonpicker-css', PROTON_FIELDS_URL.'iconpicker/css/jquery.fonticonpicker.min.css', false, 1 );
		wp_enqueue_style( 'proton-fonticonpicker-theme', PROTON_FIELDS_URL.'iconpicker/themes/dark-grey-theme/jquery.fonticonpicker.darkgrey.min.css', array('proton-fonticonpicker-css'), 1 );

		//wp_enqueue_script('proton-fonticonpicker-js', PROTON_FIELDS_URL.'iconpicker/jquery.fonticonpicker.min.js', array('jquery'), PROTON_VERSION, true);
		//wp_enqueue_script('proton-fields-iconpicker-js', PROTON_FIELDS_URL.'iconpicker/field-iconpicker.js', array('jquery','proton-fonticonpicker-js'), PROTON_VERSION, true);

		proton_enqueue_script('proton-fonticonpicker-js', PROTON_FIELDS_URL.'iconpicker/js/jquery.fonticonpicker.min.js', PROTON_VERSION, true);
		proton_enqueue_script('proton-fields-iconpicker-js', PROTON_FIELDS_URL.'iconpicker/js/field-iconpicker.min.js', PROTON_VERSION, true, 2);


		$json = PROTON_FIELDS.'iconpicker/js/font-awesome-data.json';
        $file_contents = file_get_contents( $json );
		$icons_list = json_decode( $file_contents, true );

		$fontsarray = array();

		foreach ( $icons_list as $group => $icons ) {
			if ( is_array( $icons )){
				$fontsarray[$group]= array();
				foreach ( $icons as $key => $icon ) {
					$fontsarray[$group][]= $key;
				}

			}
		}

		wp_localize_script( 'proton-framework', '_Proton__Field_iconpicker', array(
                    'fontawesome' => $fontsarray
            ));
	}


}

endif;