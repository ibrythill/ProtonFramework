<?php
// File Security Check
if ( ! defined( 'PROTON_VERSION' ) ) exit;
/**
 * Custom fields for WordPress taxonomies.
 *
 * Add custom fields to taxonomy edit screens within WordPress.
 * Also processes the custom fields as post meta when the taxonomy is saved.
 *
 * @package WordPress
 * @subpackage protonFramework
 * @category Core
 * @author ProtonThemes
 * @since 1.0.0
 *
 * TABLE OF CONTENTS
 *
 * - taxonomy_create()
 * - taxonomy_handle()
 * - taxonomy_add()
 */

if(!class_exists('_Proton__Opt_Term')):

class _Proton__Opt_Term{

	/**
	 * Holds the instance of this class.
	 */
	private static $instance;

	public static $taxboxes = array();
	public $customscreens = array( 'edit-tags.php', 'term.php' );
	private $OPT_CLASS;

	private function __construct(){

		if(get_theme_support( 'proton-opt-taxonomy' ) ){
			$taxboxes = get_theme_support( 'proton-opt-taxonomy' );
			//self::$taxboxes = array_merge( self::$taxboxes, $taxboxes[0] ) ;
		}
		$this->OPT_CLASS = new _Proton__Opt(self::$taxboxes, $this->customscreens);

		//$this->proton_meta_options();
		add_action( 'edit_category_form_fields',array( &$this, 'taxonomy_create'), 10, 2 );
		add_action( 'category_add_form_fields',array( &$this, 'taxonomy_create'), 10, 2 );
		// this saves the fields
		add_action( 'created_category',array( &$this, 'taxonomy_handle'), 10, 2);
		add_action( 'edited_category', array( &$this, 'taxonomy_handle'), 10, 2);


		add_action('init',array( &$this, 'proton_init_actions'),101);
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
		$args['type'] = 'taxonomy';
		self::$taxboxes[$tabID] = $args;
	} // End addConfig()

	/**
	 * proton_init_actions()
	 *
	 * Initialize save handles
	 *
	 * @access public
	 * @return void
	 */
	function proton_init_actions (){
		$args = array(
		  'public'   => true,
		  '_builtin' => true

		);
		$output = 'names'; // or objects
		$operator = 'and'; // 'and' or 'or'
		$taxonomies = get_taxonomies( $args, $output, $operator );

		if ( $taxonomies ) {
		  foreach ( $taxonomies  as $taxonomy ) {
		    add_action( $taxonomy.'_edit_form_fields',array( &$this, 'taxonomy_create'), 10, 2 );
			add_action( $taxonomy.'_add_form_fields',array( &$this, 'taxonomy_create'), 10, 2 );
			// this saves the fields
			add_action( 'created_'.$taxonomy,array( &$this, 'taxonomy_handle'), 10, 2);
			add_action( 'edited_'.$taxonomy, array( &$this, 'taxonomy_handle'), 10, 2);
		  }
		}
	}

	/**
	 * taxonomy_handle()
	 *
	 * Handle the saving of the custom fields.
	 *
	 * @access public
	 * @param int $post_id
	 * @return void
	 */
	function taxonomy_handle( $term_id  ) {
		if ( ! is_array( self::$taxboxes ) || count(self::$taxboxes) == 0 ) { return; }
		if(function_exists('get_term_meta') ){
			$meta_functions = array('get'=>'get_term_meta','update'=>'update_term_meta','delete'=>'delete_term_meta'); //'taxonomy';
		}else{
			$meta_functions = 'taxonomy';
		}

		//call to parent function
        $this->OPT_CLASS->save_handle(array('post_id' => $term_id, 'post_functions' => $meta_functions, 'meta_type' => 'term'));

	} // End taxonomy_handle()

	/**
	 * taxonomy_create()
	 *
	 * Add meta boxes for the protonFramework's custom fields.
	 *
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	function taxonomy_create ($tax) {
	    if ( ! is_array( self::$taxboxes ) || count(self::$taxboxes) == 0 ) { return; }
	    $t_id = (is_object($tax) ? $tax->term_id : '');
		?>

		</table><br/><br/>
			<div class="protonthemes-opts protonthemes-opts-taxonomy">
			<?php
					echo $this->OPT_CLASS->render_container($t_id);
					?>
			</div>
		<table>

		<?

	} // End taxonomy_create()

} // End class

endif;
