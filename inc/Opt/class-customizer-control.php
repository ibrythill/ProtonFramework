<?php
// File Security Check
if ( ! defined( 'PROTON_VERSION' ) ) exit;


if(class_exists('WP_Customize_Control')){
class PROTON_Custom_Control extends WP_Customize_Control
 {
    private $proton_optionbox = '';

    public function __construct($manager, $id, $args = array(), $options = array())
    {
        $this->proton_optionbox = $options;

        parent::__construct( $manager, $id, $args );
    }

    /**
     * Render the content of the category dropdown
     *
     * @return HTML
     */
    public function render_content()
       {
            $field_class = '_Proton__Field_'.$this->type;
            $row_css_class = 'proton-custom-field';
            $this->proton_optionbox['data'] = $this->get_link(  );

			if(!class_exists($field_class)){
    	   	 require_once(PROTON_DIR.'fields/'.$this->type.'/proton.field.'.$this->type.'.php');
    	    }
    	    if(class_exists($field_class)){
    	    	$render = new $field_class($this->proton_optionbox, $this->value(), $row_css_class, 'customizer');
				$output = $render->render();
			}

			echo $output;

       }

    /**
     * Render the content of the category dropdown
     *
     * @return HTML
     */
    public function enqueue()
       {

	    	$field_class = '_Proton__Field_'.$this->type;

            wp_enqueue_style( 'proton_admin_css', PROTON_URL . 'assets/css/admin.general.style.css' );
			wp_enqueue_script( 'proton-custom-fields', PROTON_URL . 'assets/js/proton-custom-fields.js', array( 'jquery' ) );

			if(!class_exists($field_class)){
    	   	 require_once(PROTON_DIR.'fields/'.$this->type.'/proton.field.'.$this->type.'.php');
    	    }
    	    if(class_exists($field_class)){
    	    	$enqueue = new $field_class('','','','');
				$enqueue->enqueue();
			}

	    }
 }
 }