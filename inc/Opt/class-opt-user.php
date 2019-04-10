<?php
namespace Proton\Opt;

/**
 * Custom fields for WordPress taxonomies.
 *
 * Add custom fields to user edit screens within WordPress.
 * Also processes the custom fields as user is saved.
 *
 * @package WordPress
 * @subpackage protonFramework
 * @category Core
 * @author ProtonThemes
 * @since 1.0.0
 *
 * TABLE OF CONTENTS
 *
 * - user_create()
 * - user_handle()
 * - user_add()
 */

class User{

	/**
	 * Holds the instance of this class.
	 */
	private static $instance;

	public static $userboxes = array();
	public $customscreens = array( 'profile.php', 'wp-login.php', 'wp-register.php', 'user-edit.php' );
	private $OPT_CLASS;

	private function __construct(){

		if(get_theme_support( 'proton-opt-user' ) ){
			$userboxes = get_theme_support( 'proton-opt-user' );
			//self::$userboxes = array_merge( self::$userboxes, $userboxes[0] ) ;
		}
		$this->OPT_CLASS = new _Proton__Opt(self::$userboxes, $this->customscreens);

		// this adds the fields
		add_action('show_user_profile',array( &$this, 'user_create') );
		add_action('edit_user_profile',array( &$this, 'user_create') );
		//add_action('register_form',array( $this, 'user_add') );

		// this saves the fields
		add_action('personal_options_update',array( &$this, 'user_handle'));
		add_action('edit_user_profile_update', array( &$this, 'user_handle'));
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
	 * Adds taxbox options to variable
	 *
	 * @access public
	 * @return void
	 */
	public static function addConfig($tabID, $args = array()){
		$args['type'] = 'user';
		self::$userboxes[$tabID] = $args;
	} // End addConfig()

	/**
	 * user_handle()
	 *
	 * Handle the saving of the custom fields.
	 *
	 * @access public
	 * @param int $post_id
	 * @return void
	 */
	function user_handle( $user_id  ) {
	    if ( ! is_array( self::$userboxes ) || count(self::$userboxes) == 0 ) { return; }

	    $meta_functions = array('get'=>'get_user_meta','update'=>'update_user_meta','delete'=>'delete_user_meta');

		//call to parent function
        $this->OPT_CLASS->save_handle(array('post_id' => $user_id, 'post_functions' => $meta_functions, 'meta_type' => 'user'));

	} // End user_handle()

	/**
	 * user_create()
	 *
	 * Add meta boxes for the protonFramework's custom fields.
	 *
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	function user_create ($user) {

		if ( ! is_array( self::$userboxes ) || count(self::$userboxes) == 0 ) { return; }
	    $t_id = $user->ID;
		?>

			<div class="protonthemes-opts protonthemes-opts-user">
			<?php
					echo $this->OPT_CLASS->render_container($t_id);
					?>
			</div>


		<?php

	} // End user_create()

} // End class


