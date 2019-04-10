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
 * - meta_options()
 * - meta_create()
 * - meta_fields()
 * - meta_handle()
 * - meta_add()
 */



	class Meta{

		/**
		 * Holds the instance of this class.
		 */
		private static $instance;

		public static $metaboxes = array();
		public $customscreens = array( 'post.php', 'post-new.php', 'page-new.php', 'page.php' );
		private $OPT_CLASS;

		private function __construct(){

			if(get_theme_support( 'proton-opt-meta' ) ){
				$metaboxes = get_theme_support( 'proton-opt-meta' );
				//self::$metaboxes = array_merge( self::$metaboxes, $metaboxes[0] ) ;
			}

			//self::$metaboxes = apply_filters('proton-opt-meta', $metaboxes);

			$this->OPT_CLASS = new _Proton__Opt(self::$metaboxes, $this->customscreens);

			//add_action( 'admin_enqueue_scripts', array( &$this, 'meta_options'), 10 );

			add_action( 'save_post', array( &$this, 'meta_handle'), 10 );
			add_action( 'update_post', array( &$this, 'meta_handle'), 10 );
			add_action( 'save_attachment', array( &$this, 'meta_handle'), 10 );
			add_action( 'admin_menu', array( &$this, 'meta_add'), 10 ); // Triggers meta_create()
		}


		/**
		 * Returns the instance.
		 *
		 * @since  1.1.0
		 * @access public
		 * @return object
		 */
		public static function get_instance() {

			if ( !self::$instance )
				self::$instance = new self;

			return self::$instance;
		}

		/**
		 * addConfig()
		 *
		 * Adds metabox options to variable
		 *
		 * @access public
		 * @return void
		 */
		public static function addConfig($tabID, $args = array()){
			$args['type'] = 'meta';
			self::$metaboxes[$tabID] = $args;
		} // End addConfig()

		/**
		 * meta_create()
		 *
		 * Creates metabox area and tabbs
		 *
		 * @access public
		 * @param array $post
		 * @param array $callback
		 * @return $output
		 */
		function meta_create( $post, $metabox ) {
			if ( ! is_array( self::$metaboxes ) || count(self::$metaboxes) == 0 ) { return; }
		    echo $this->OPT_CLASS->render_container($post->ID, array($metabox['args']['context'],$metabox['args']['priority']));
		    return;
		} // End meta_create()


		/**
		 * meta_handle()
		 *
		 * Handle the saving of the custom fields.
		 *
		 * @access public
		 * @param int $post_id
		 * @return void
		 */
		function meta_handle( $post_id ) {
		    if ( ! is_array( self::$metaboxes ) || count(self::$metaboxes) == 0 ) { return; }
		    if ( isset( $_POST['action'] ) && $_POST['action'] == 'editpost' ) {
		        if ( ( get_post_type() != '' ) && ( get_post_type() != 'nav_menu_item' ) ) {
					//define meta functions
					$meta_functions = array('get'=>'get_post_meta','update'=>'update_post_meta','delete'=>'delete_post_meta');

					//check if revision
					//if ( $the_post = wp_is_post_revision($post_id) )
					//$post_id = $the_post;

					//call to parent function
			        $this->OPT_CLASS->save_handle(array('post_id' => $post_id, 'post_functions' => $meta_functions, 'meta_type' => 'post'));
		        }
		    }
		} // End meta_handle()

		/**
		 * meta_add()
		 *
		 * Add meta boxes for the protonFramework's custom fields.
		 *
		 * @access public
		 * @since 1.0.0
		 * @return void
		 */
		public function meta_add () {
			global $shortname;

			$proton_metaboxes = self::$metaboxes;
			if ( ! $proton_metaboxes || ! is_array( $proton_metaboxes  ) ){return;}
			$metabox_positions = array();
			foreach ($proton_metaboxes as $key => $opt) {
				if(array_key_exists('limit', $opt)){
			        if(get_post_type() !== $opt['limit']){
				        continue;
			        }
		        }
		        if(!array_key_exists('meta', $opt)){
			        $opt['meta'] = array('normal','core');
		        }
				$metabox_positions_temp[] = $opt['meta'];
			}
			$metabox_positions = array_map("unserialize", array_unique(array_map("serialize", $metabox_positions_temp)));

		    if ( function_exists( 'add_meta_box' ) ) {
		    	if ( function_exists( 'get_post_types' ) ) {
		    		$custom_post_list = get_post_types();
		    		// Get the theme name for use in multiple meta boxes.
		    		$theme_name = $shortname;

					foreach ( $custom_post_list as $type ) {
						foreach ($metabox_positions as $key => $metabox_position) {
							$settings = array(
								'id' => 'protonthemes-opts-'.$key,
								'title' => sprintf( __( '%s Custom Settings', PROTON_SLUG ), $theme_name ),
								'callback' => array( &$this, 'meta_create'),
								'screen' => $type,
								'context' => $metabox_position[0],
								'priority' => $metabox_position[1],
								'callback_args' => array(
									'context' => $metabox_position[0],
									'priority' => $metabox_position[1]
								)
							);


						// Allow child themes/plugins to filter these settings.
						$settings = apply_filters( 'protonthemes_metabox_settings', $settings, $type, $settings['id'] );
						add_meta_box( $settings['id'], $settings['title'], $settings['callback'], $settings['screen'], $settings['context'], $settings['priority'], $settings['callback_args'] );
						add_filter( 'postbox_classes_'.$settings['screen'].'_protonthemes-opts-'.$key, array( &$this, 'add_opts_classes') );
						// if(!empty($proton_metaboxes)) Temporarily Removed
						}
					}
		    	} else {

		    		add_meta_box( 'protonthemes-settings', sprintf( __( '%s Custom Settings', PROTON_SLUG ), $theme_name ), array( &$this, 'meta_create'), 'post', 'normal' );
		        	add_meta_box( 'protonthemes-settings', sprintf( __( '%s Custom Settings', PROTON_SLUG ), $theme_name ), array( &$this, 'meta_create'), 'page', 'normal' );
		    	}
		    }
		} // End meta_add()

		public function add_opts_classes ($classes=array()) {
			if( !in_array( 'protonthemes-opts', $classes ) )
		        $classes[] = 'protonthemes-opts';

		    return $classes;
		}

	} // End Class


