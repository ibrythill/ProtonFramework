<?php
// File Security Check
if ( ! defined( 'PROTON_VERSION' ) ) exit;

if(!class_exists('_Proton__Field_map')):

class _Proton__Field_map extends _Proton__Field{

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

		$zoom = array_key_exists('zoom', $options['options']) ? $options['options']['zoom'] : 10;


        //box main content
        $options['field'] = '
		  		<input class="proton_address" id="'.esc_attr( $ID ).'-address" size="20" type="text" placeholder="'.__('Search address or show marker nearest',PROTON_SLUG).'" name="'.$options['name'].'[address]" value="'.( array_key_exists('address', $options['value']) ? esc_attr( $options['value']['address'] ) : '').'">
        <div id="'.esc_attr( $ID ).'-map" class="proton_map_canvas" data-zoom="'.$zoom.'"></div>
		  <div class="proton_latlong">
		    	<input class="proton_lat" id="'.esc_attr( $ID ).'-lat" size="20" type="text" placeholder="'.__('Latitude',PROTON_SLUG).'" name="'.$options['name'].'[lat]" value="'.( array_key_exists('lat', $options['value']) ? esc_attr( $options['value']['lat'] ) : 0).'" >
				<input class="proton_lng" id="'.esc_attr( $ID ).'-lng" size="20" type="text" placeholder="'.__('Longitude',PROTON_SLUG).'" name="'.$options['name'].'[lng]" value="'.( array_key_exists('lng', $options['value']) ? esc_attr( $options['value']['lng'] ) : '').'">
		  </div>';

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
		proton_enqueue_script('proton-googlemap', 'https://maps.googleapis.com/maps/api/js?libraries=places,geometry', PROTON_VERSION, true, 1);
		proton_enqueue_script('proton-fields-map-js', PROTON_FIELDS_URL.'map/js/field-map.min.js', PROTON_VERSION, true, 2);
	}

}

endif;