<?php
// File Security Check
if ( ! defined( 'PROTON_VERSION' ) ) exit;
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

if(!class_exists('_Proton__Opt_Meta')):

	class _Proton__Opt_Meta extends _Proton__Opt{

		/**
		 * Whether this is a new post.  Once the post is saved and we're
		 * no longer on the `post-new.php` screen, this is going to be
		 * `false`.
		 *
		 * @since  1.0.0
		 * @access public
		 * @var    bool
		 */
		public $is_new_post = false;

		/**
		 * opt_type
		 *
		 * (default value: 'default')
		 *
		 * @var string
		 * @access public
		 */
		public $type = 'meta';


		/**
		 * Returns the instance.
		 *
		 * @access public
		 * @static
		 * @return object
		 */
		public static function get_instance() {

			if ( is_null( self::$instance ) ) {
				self::$instance = new self;
				self::$instance->setup();
				self::$instance->includes();
				self::$instance->setup_actions();
			}

			return self::$instance;
		}

		public function register_meta() {
			// If this is a new post, set the new post boolean.
			if ( 'load-post-new.php' === current_action() )
				$this->is_new_post = true;

			$post_type = get_current_screen()->post_type;

			// Action hook for registering managers.
			do_action( 'proton/opt/register', $this, $post_type );

			// Loop through the managers to see if we're using on on this screen.
			foreach ( $this->managers as $manager ) {

				if ( ! in_array( $post_type, (array) $manager->post_type ) || 'meta' !== $manager->type) {
					$this->unregister_manager( $manager->name );
					continue;
				}


				// Sort controls and sections by priority.
				uasort( $manager->controls, array( $this, 'priority_sort' ) );
				uasort( $manager->sections, array( $this, 'priority_sort' ) );
			}

		}
		/**
		 * Initial plugin setup.
		 *
		 * @since  1.0.0
		 * @access private
		 * @return void
		 */
		public function setup() {
			parent::setup();
			Proton::register_opt(self::$instance);
		}

		public function includes() {

			// Call the register function.
			add_action( 'load-post.php'			,	array( $this, 'register' ), 95 );
			add_action( 'load-post-new.php'		, 	array( $this, 'register' ), 95 );

			//add_action( 'proton/opt/register', array( $this, 'register_meta' ), 95 );
			parent::includes();
		}
		public function register() {
			$this->register_meta();
		}
		public function setup_actions() {
			parent::setup_actions();
			add_action( 'save_post', array( $this, 'update' ) );
			//add_action( 'save_post', array( $this, 'meta_handle'), 10 );
			//add_action( 'save_attachment', array( $this, 'meta_handle'), 10 );

			add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ), 5 );
		}
		public function update($post_id) {
			$do_autosave = defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE;
			$is_autosave = wp_is_post_autosave( $post_id );
			$is_revision = wp_is_post_revision( $post_id );

			if ( $do_autosave || $is_autosave || $is_revision )
				return;

			parent::update($post_id);
		}

		/**
		 * Callback function for adding meta boxes.  This function adds a meta box
		 * for each of the managers.
		 *
		 * @since  1.0.0
		 * @access public
		 * @param  string  $post_type
		 * @return void
		 */
		public function add_meta_boxes( $post_type ) {

			foreach ( $this->managers as $manager ) {

				// If the manager is registered for the current post type, add a meta box.
				if ( in_array( $post_type, (array) $manager->post_type ) && $manager->check_capabilities() ) {

					add_meta_box(
						"proton-opt-ui-{$manager->name}",
						$manager->label,
						array( $this, 'meta_box' ),
						$post_type,
						$manager->context,
						$manager->priority,
						array( 'manager' => $manager )
					);
				}
			}
		}

		/**
		 * Displays the meta box.  Note that the actual content of the meta box is
		 * handled via Underscore.js templates.  The only thing we're outputting here
		 * is the nonce field.
		 *
		 * @since  1.0.0
		 * @access public
		 * @param  object  $post
		 * @param  array   $metabox
		 * @return void
		 */
		public function meta_box( $post, $metabox ) {

			$manager = $metabox['args']['manager'];

			$manager->post_id = $this->post_id = $post->ID;

			// Nonce field to validate on save.
			wp_nonce_field( "proton_opt_{$manager->name}_nonce", "proton_opt_{$manager->name}" );
		}


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


	} // End Class

endif;
