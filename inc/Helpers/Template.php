<?php
// File Security Check
if ( ! defined( 'PROTON_VERSION' ) ) exit;

if(!class_exists('_Proton__Templates')):

class _Proton__Templates{

	/**
	 * Holds the instance of this class.
	 */
	private static $instance;

	public static $templates = array();

	/**
	 * Template Constructor.
	*/
	private function __construct( ){

		add_action( 'admin_footer', array( $this, 'enqueue_templates' ) );
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
	 * enqueue()
	 *
	 * Enqueues templates
	 *
	 * @access public
	 * @return void
	 */
	public static function enqueue($tID, $content){
		self::$templates[$tID] = $content;//__::template($content);
	} // End addConfig()

	/**
	 * Dumps the contents of template-data.php into the foot of the document.
	 * WordPress itself function-wraps the script tags rather than including them directly
	 * ( example: https://github.com/WordPress/WordPress/blob/master/wp-includes/media-template.php )
	 * but this isn't necessary for this example.
	 */
	public function enqueue_templates() {
		require(PROTON_INC.'template-modal.php');


		//echo $mustache = Underscore::template('Hello {{$planet}}!', array('planet'=>'World'),$pattern);

		//$mustache = self::compile('Hello  {{planet}}! <#each costam {#> {{{costam[$key][1]}}}! <# } #>');
		/*echo $mustache(
		array('planet'=>'World',
				'costam'=> array(
							'costam'=>array(
								'xx','ss'
							)
						)
					)
				);
				*/
		$content = '';

		foreach (self::$templates as $key => $template) {
			$content .= self::build_template($key, $template)."\n";
		}

		echo $content;
	} // End add_templates()

	/**
	 * Render template.
	*/
	public static function render($options){
		$template = (  array_key_exists('template', $options) ? $options['template'] : 'default');
		$template = (  is_file(PROTON_INC.'template-' . $template . '.php') ? $template : 'default');
		$output = '';
        //load default template
        require(PROTON_INC.'template-' . $template . '.php');

        return $output;

	}

	/**
	 * Compile template.
	*/
	public static function compile($templateString, $data = array()){
		$patterns = array(
			'<#([\s\S]+?)#>', //evaluate
	        '\{\{([^\}]+?)\}\}(?!\})', //escape
	        '\{\{\{([\s\S]+?)\}\}\}' //interpolate
		);

		$pattern = '~(?<escape>\{\{([^\}]+?)\}\}(?!\}))|(?<interpolate>\{\{\{([\s\S]+?)\}\}\})|(?<evaluate><#([\s\S]+?)#>)|$~';

		$templateString = preg_replace_callback($pattern, function ($match) {
            //var_dump($match);
            if (!empty($match['escape'])){
                return sprintf('<?php echo html_entity_decode($%s) ?>', trim($match[2]));
            }

            if (!empty($match['interpolate'])){
                return sprintf('<?php echo $%s ?>', trim($match[4]));
            }

            if (!empty($match['evaluate'])){
                $pattern = '~(?<each>each (.+?))|$~';
                $matchString = preg_replace_callback($pattern, function ($match) {
	                if (!empty($match['each'])){
		                return sprintf('foreach($%s as $key => $item){', trim($match[2]));
		            }
                }, $match[6]);
                return sprintf('<?php %s ?>', trim($matchString));
            }
        }, $templateString);

        $templateFunction = create_function(
            '$data',
            'extract($data); ob_start(); ?>'. $templateString . '<?php return ob_get_clean();'
        );

        return $templateFunction;

	}

	/**
	 * Print template.
	*/
	public static function build_template($tID, $templateString){

        return '<script type="text/html" id="tmpl-fp-field-'.$tID.'">'.$templateString.'</script>';

	}


} // End Class

endif;
