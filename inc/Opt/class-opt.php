<?php
namespace Proton\Opt;

/**
 * Custom fields for WordPress write panels.
 *
 * Add custom fields to various post types "Add" and "Edit" screens within WordPress.
 * Also processes the custom fields as post meta when the post is saved.
 *
 * @package WordPress
 * @subpackage protonFramework
 * @category Core
 * @author ProtonThemes
 * @since 1.0.0
 *
 * TABLE OF CONTENTS
 *
 * - opt_update_boxes()
 * - render_field()
 * - save_handle()
 * - save_handle()
 * - fieldtypes()
 * - enqueue()
 */

class Base{

	/**
	 * Holds the instance of this class.
	 */
	private static $instance;


	public $proton_opt = array();
	public $customscreens = array();

	public function __construct($proton_opt = array(),$customscreens = array()){

		$this->proton_opt = $proton_opt;
		$this->customscreens = $customscreens;

		add_action( 'admin_enqueue_scripts', 	array( $this, 'enqueue'), 12, 1 );
		add_action( 'admin_enqueue_scripts', 	array( $this, 'fields_enqueue'), 13, 1 );

	}

	public function render_container($item_id, $limit = null) {
	    $proton_opt_temp = $this->proton_opt;
	    $proton_opt = array();
	    $output = '';

		// Array sanity check.
		if ( ! is_array( $proton_opt_temp ) ) { $proton_opt_temp = array(); return; }

		//additional check
		foreach ($proton_opt_temp as $key => $opt) {
			//check if has fields
			if(!is_array($opt['fields'])){
				continue;
			}
			//set theme
			if(array_key_exists('theme', $opt) && '' !== $opt['theme']){
				$opt['theme'] = preg_replace('/\s+/', '-', $opt['theme']);
			}else{
				$opt['theme'] = 'Blue-Grey';
			}
			//limitations
	        if('meta' === $opt['type']){
		        if(array_key_exists('meta', $opt)){
					//define default values if missing
					if(!array_key_exists(0, $opt['meta'])){ $opt['meta'][0] = 'core'; }
					if(!array_key_exists(1, $opt['meta'])){ $opt['meta'][1] = 'default'; }
					//check if element should be visible
					if($opt['meta'][0] !== $limit[0] || $opt['meta'][1] !== $limit[1]){
						continue;
			        }
		        }else{
			        if('normal' !== $limit[0] || 'core' !== $limit[1]){
						continue;
			        }
		        }
		        if(array_key_exists('limit', $opt)){
			        //check post type limitation
			        if(!in_array(get_post_type(), $opt['limit'])){
				        continue;
			        }
		        }
	        }
	        //add to array
	        $proton_opt[$key] = $opt;
	    }
		if ( empty( $proton_opt ) ) { return; }
	    // Add nonce for custom fields.
	    $output .= wp_nonce_field( 'protonframework', 'protonframework-nonce', true, false );

	    // Add tabs.
	    $output .= '<div class="proton-tab-area ui-tabs ui-widget ui-widget-content ui-corner-all">' . "\n";

	    $output .= '<ul '. ( count($proton_opt) <= 1 ? 'style="display:none"' : '' ) .' class="proton-tabs ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">' . "\n";

		//render tabs
        foreach ($proton_opt as $key => $opt) {
            $output .= '<li class="proton-tab proton-tab-' . $key . '-' . $item_id . ' ui-state-default ui-corner-top '. 'theme-'.$opt['theme'] .'" data-theme="'. $opt['theme'] .'"><a class="proton-tab-anchor" href="#proton-tab-' . $key . '-' . $item_id . '"><i id="' . $key . '-' . $item_id . '" class="livicon shadowed" data-s="18" data-n="' . $opt['icon'] . '" data-c="#fff" data-hc="#fff" style="width: 18px; height: 18px;"></i>' . $opt['title'] . '</a></li>' . "\n";

        }
	    $output .= '</ul>' . "\n";

		//render tab content
	    foreach ($proton_opt as $key => $opt) {
	        $output .= '<div class="proton-tab-content" id="proton-tab-' . $key . '-' . $item_id . '">' . "\n";

		        $output .= '<div class="proton-tabinfo"><div class="proton-opt-table">' . "\n";
				$output .= '<div class="proton-tabinfo-title">' . $opt['title'] . '</div>' . "\n";
				$output .= '<div class="proton-tabinfo-desc">' . $opt['desc'] . '</div></div>' . "\n";
				$output .= '</div>' . "\n";


		    //iterate all fields
		    foreach ( $opt['fields'] as $opt_field ) {
				//get field value for opt type
				switch ( $opt['type'] ) {
				    case 'meta':
				        $opt_field['value'] = get_post_meta( $item_id , $opt_field['id'] , true );
				        break;
				    case 'menu':
				        $opt_field['value'] = get_post_meta( $item_id , $opt_field['id'] , true );
					   	$opt_field['id'] = $opt_field['id'].'-'.$item_id;
				        break;
				    case 'user':
				        $opt_field['value'] = get_user_meta( $item_id , $opt_field['id'] , true );
				        break;
				    case 'taxonomy':
				        if(function_exists('get_term_meta')){
					        $opt_field['value'] = get_term_meta( $item_id , $opt_field['id'] , true );
				        }else{
							$taxonomy_values = get_option( "taxonomy_$item_id");
							$opt_field['value'] = array_key_exists($opt_field['id'], $taxonomy_values) ? $taxonomy_values[$opt_field['id']] : '';
				        }

				        break;
				}
				//set parent type
	    	    $opt_field['parent'] = array( $opt['type'], $item_id );

				//render field
	        	$output .= self::render_field ( $opt_field );
		    }
		    $output .= '</div><!--/#proton-tab-' . $key . '-->' . "\n\n";

	     }

	    // Allow themes/plugins to add tabs to protonFramework custom fields.
	    $output = apply_filters( 'protonframework_custom_field_tab_content', $output );

	    $output .= '</div>' . "\n";

	    return $output;
	} // End meta_create()


	/**
	 * render_field()
	 *
	 * Create markup for custom fields based on the given arguments.
	 *
	 * @access public
	 * @param array $proton_opt
	 * @param array $callback
	 * @param string $token (default: 'general')
	 * @return string $output
	 */
	public static function render_field ( $options ) {
		//check array
	    if ( ! is_array( $options ) ) { return; }

		$output = '';

    	// Setup CSS classes to be added to each table row.
    	//$row_css_class = 'proton-custom-field';

    	if( isset( $options['type'] ) && ( in_array( $options['type'], self::fieldtypes() ) ) ) {

    	    $options['value'] = ($options['value'] == '' ? $options['default'] : $options['value']);

			$options['classes'] = ( array_key_exists('classes', $options) ? $options['classes'] : '');
    	    // Add a dynamic CSS class to each row in the table.
    	    $options['classes'] .= 'proton-field-type-' . strtolower( $options['type'] ) . ' ';

    	    $field_class = '_Proton__Field_'.$options['type'];

    	    if(method_exists($field_class, 'make')){
    	    	//$class = new $field_class();//::make($options);
    	    	//$options = $class->make($options);
    	    	$options = $field_class::make($options);
    	    	//Display template
		        $output .= _Proton__Templates::render($options);

			}
    	} // End IF Statement



	    return $output;
	} // End render_field()


	/**
	 * save_handle()
	 *
	 * Handle the saving of the custom fields.
	 *
	 * @access public
	 * @param int $post_id
	 * @return void
	 */
	public function save_handle( $args) {
	    $pID = '';

	    extract( wp_parse_args( $args,
			array (
				'post_id' => '',
				'meta_type' => 'post'
			) )
		);

	    $proton_opt = $this->proton_opt;

		if ( ! $proton_opt || ! is_array( $proton_opt  ) ){return;}

	    // Don't continue if we don't have a valid post ID.
	    if ( $post_id == 0) return;

	        if ( isset($_POST['protonframework-nonce']) && wp_verify_nonce( $_POST['protonframework-nonce'], 'protonframework' ) ) {
	            foreach ( $proton_opt as $key => $opt ) {
			        foreach ( $opt['fields'] as $opt_field ) {
				        if( isset( $opt_field['type'] ) && ( in_array( $opt_field['type'], self::fieldtypes() ) ) ) {
					        $custom_id = array_key_exists('name', $opt_field) ? $opt_field['name'] : $opt_field['id'];
					        //check opt type
					        if( 'menu' === $opt['type'] ){
			    				$custom_id = $custom_id.'-'.$post_id;
		    				} // End IF Statement

	    					$field_class = '_Proton__Field_'.$opt_field['type'];

	    					$args = array(
		    					'post_id' => $post_id,
								'meta_type' => $meta_type,
								'custom_id' => $custom_id,
								'opt_field' => $opt_field
	    					);

			        	    $posted_value = $field_class::save_handle($args);

				        } // End IF Statement
			        } // End FOREACH Loop
			    } // End FOREACH Loop
			} // End IF Statement



	} // End save_handle()


	/**
	 * fieldtypes()
	 *
	 * Return a filterable array of supported field types.
	 *
	 * @access public
	 * @return void
	 */
	public static function fieldtypes() {
		return apply_filters( 'fieldtypes', array( 'text', 'calendar', 'time', 'number', 'select', 'radio', 'checkbox', 'textarea', 'editor', 'color', 'radioimg', 'upload', 'gallery', 'multi', 'info', 'slider', 'iconpicker', 'extended', 'timerange' , 'combo', 'map') );
	} // End fieldtypes()



	/**
	 * enqueue()
	 *
	 * Enqueue JavaScript and CSS files used with the custom fields.
	 *
	 * @access public
	 * @param string $hook
	 * @since 2.0.0
	 * @return void
	 */
	public function enqueue ( $hook ) {

		$proton_opts = $this->proton_opt;

		if ( !$this->proton_opt || ! is_array( $this->proton_opt  ) ){ return; }
		//if ( !$this->customscreens || ! is_array( $this->customscreens  ) ){return;}

		//JS
		//wp_register_script( 'proton-opt', PROTON_URL . 'assets/js/proton-opt.js', array( 'jquery', 'jquery-ui-tabs' ) );

  		//wp_enqueue_script( 'proton-opt' );


	} // End enqueue()


	/**
	 * fields_enqueue()
	 *
	 * Enqueue JavaScript and CSS files used with the custom fields.
	 *
	 * @access public
	 * @param string $hook
	 * @since 2.0.0
	 * @return void
	 */
	public function fields_enqueue ( $hook ) {

		$proton_opts = $this->proton_opt;

		if ( !$this->proton_opt || ! is_array( $this->proton_opt  ) ){ return; }

		if ( in_array( $hook, $this->customscreens ) ) {

			foreach ($this->proton_opt as $key => $opt) {
				if ( !$opt || ! is_array( $opt  ) ){return;}
				if(!is_array($opt['fields'])){
					continue;
				}
	        	foreach ( $opt['fields'] as $opt_field ) {
		        	$field_class = '_Proton__Field_'.$opt_field['type'];
					if( isset( $opt_field['type'] ) && ( in_array( $opt_field['type'], $this->fieldtypes() ) ) ){

		        	    if(method_exists($field_class, 'queue')){
							//$enqueue = new $field_class('','','','');
							//$class = new $field_class();//::make($options);
							//$class->queue();
							$field_class::queue();
						}
	        	    }
	        	    if(array_key_exists('fields', $opt_field)){
		        	    foreach ( $opt_field['fields'] as $opt_field_field ) {
				        	$field_class = '_Proton__Field_'.$opt_field_field['type'];
							if( isset( $opt_field_field['type'] ) && ( in_array( $opt_field_field['type'], $this->fieldtypes() ) ) ){

				        	    if(method_exists($field_class, 'queue')){
									//$enqueue = new $field_class('','','','');
									//$class = new $field_class();//::make($options);
									//$class->queue();
									$field_class::queue();
								}
			        	    }
			        	}
	        	    }
	        	}
	        }
	  	}
	} // End fields_enqueue()








} // End Class

