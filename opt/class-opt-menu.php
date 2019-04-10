<?php
// File Security Check
if ( ! defined( 'PROTON_VERSION' ) ) exit;
/**
 * Custom fields for WordPress menu items
 *
 * Add custom fields to menu items edit screens within WordPress.
 * Also processes the custom fields as menu item meta when the menu item is saved.
 *
 * @package WordPress
 * @subpackage protonFramework
 * @category Core
 * @author ProtonThemes
 * @since 2.2.0
 *
 * TABLE OF CONTENTS
 *
 * - menu_walker()
 * - menu_handle()
 * - menu_create()
 */

if(!class_exists('_Proton__Opt_Menu')):

class _Proton__Opt_Menu{

	/**
	 * Holds the instance of this class.
	 */
	private static $instance;

	public static $menuboxes = array();
	public $customscreens = array( 'nav-menus.php' );
	private static $OPT_CLASS;

	private function __construct(){

		if(get_theme_support( 'proton-opt-menu' ) ){
			$menuboxes = get_theme_support( 'proton-opt-menu' );
			//self::$menuboxes = array_merge( self::$menuboxes, $menuboxes[0] ) ;
		}

		self::$OPT_CLASS = new _Proton__Opt(self::$menuboxes, $this->customscreens);


		add_action( 'protonmenu_menu_item_options' , array( $this , 'menu_create' ), 10, 1 );
		add_filter( 'wp_edit_nav_menu_walker' ,		 array( $this , 'menu_walker' ),  1, 2 );

		add_action( 'wp_update_nav_menu_item' ,		 array( $this , 'menu_handle' ), 10, 3 );
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
		$args['type'] = 'menu';
		self::$menuboxes[$tabID] = $args;
	} // End addConfig()

	/**
	 * menu_walker()
	 *
	 * replace admin menu walker class.
	 *
	 * @access public
	 * @param string $hook
	 * @since 2.0.0
	 * @return void
	 */
	public function menu_walker( $className ){
		return 'ProtonMenuWalkerEdit';
	} // function

	/**
	 * menu_handle()
	 *
	 * Appearance > Menus : save custom menu options
	 * Saves custom menu item options as meta.
	 *
	 * @access public
	 * @param string $hook
	 * @since 2.0.0
	 * @return void
	 */
	public function menu_handle( $menu_id, $menu_item_db_id, $args ){
		if ( ! is_array( self::$menuboxes ) || count(self::$menuboxes) == 0 ) { return; }
        $meta_functions = array('get'=>'get_post_meta','update'=>'update_post_meta','delete'=>'delete_post_meta');

		//call to parent function
        self::$OPT_CLASS->save_handle(array('post_id' => $menu_item_db_id, 'post_functions' => $meta_functions, 'meta_type' => 'post'));

	} // End menu_handle()

	/**
	 * menu_create()
	 *
	 * Renders custom field types for custom options item.
	 *
	 * @access public
	 * @param string $hook
	 * @since 2.2.0
	 * @return void
	 */
	public function menu_create( $item_id ){
		if ( ! is_array( self::$menuboxes ) || count(self::$menuboxes) == 0 ) { return; }
		?>
		<div class="clear"></div>
			<div class="proton_clear">
				<hr class="separator">

				<div class="protonthemes-opts protonthemes-opts-menu">
					<h3><?php _e( 'Additional setting', PROTON_SLUG ); ?></h3>
					<?php
						echo self::$OPT_CLASS->render_container($item_id);
						?>
				</div>
			</div>
	<?php
	} // End menu_create()

} // End Class

endif;
