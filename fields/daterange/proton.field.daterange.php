<?php
class _Proton__Field_daterange{	
	
	/**
	 * Field Constructor.
	*/
	function __construct( $field = array(), $value ='' , $row_css_class, $template_to_show){
		$this->field = $field;
		$this->value = $value;
		$this->rowcss = $row_css_class;
		$this->template = $template_to_show;
	}
	
	/**
	 * Field Render Function.
	*/
	function render( $meta = false ){
$this->ID = 'protonthemes_' . $this->field['name'];
		
		if( $this->field['required'] ) { $required = 'data-check-field="protonthemes_'.$this->field['required'][0].'" data-check-comparison="'.$this->field['required'][1].'" data-check-value="'.$this->field['required'][2].'"'; $reqclass = 'hiddenFold';}

        	        $output .= "\t".'<div class="meta-body ' . $this->rowcss . ' '.$reqclass.'" '.$required.'>';
        	        $output .= "\t\t".'<div class="proton_metabox_title"><label for="'.esc_attr( $this->ID ).'">'.$this->field['label'].'</label></div>'."\n";
        	        $output .= "\t\t".'<div class="proton_metabox_desc">'.$this->field['desc'] .'<br/>'. $add_counter .'</div>'."\n";
        	        $output .= "\t\t".'<div class="clear"></div>';
        	        $output .= "\t\t".'<div class="proton_metabox_content">';
        	        $output .= "\t\t".'<input '.$this->field['custom'] .' class="proton_input_daterange '.$this->field['class'] .'" type="text" name="'.$this->field['name'].'[]" id="'.esc_attr( $this->ID ).'-1" value="'.esc_attr( $this->value[0] ).'"><i class="livicon shadowed proton_input_daterange_button" data-s="25" data-n="calendar" data-c="#03A9F4" data-hc="#01579B" style="margin-left:5px;cursor:pointer"></i>';
        	         $output .= "\t\t".'<span> - </span>';
        	        $output .= "\t\t".'<input '.$this->field['custom'] .' class="proton_input_daterange '.$this->field['class'] .'" type="text" name="'.$this->field['name'].'[]" id="'.esc_attr( $this->ID ).'-2" value="'.esc_attr( $this->value[1] ).'"><i class="livicon shadowed proton_input_daterange_button" data-s="25" data-n="calendar" data-c="#03A9F4" data-hc="#01579B" style="margin-left:5px;cursor:pointer"></i>';
        	        $output .= "\t\t" . '<input type="hidden" name="datepicker-image" value="' . PROTON_URL . 'images/calendar.gif" />';
        	        $output .= '</div>'."\n";
        	        $output .= "\t".'<hr class="separator">'."\n";
        	        $output .= "\t".'</div>'."\n";
		return $output;
	}
	
	
	/**
	 * Enqueue Function.
	*/
	function enqueue(){
		
		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_script('proton-fields-daterange-js', PROTON_FIELDS_URL.'daterange/proton.field.daterange.js', array('jquery'), PROTON_VERSION, true);
		
		wp_register_style('jquery-ui-css',PROTON_URL.'css/jquery-ui-datepicker.css');
		wp_enqueue_style( 'jquery-ui-css' );
		wp_enqueue_style( 'jquery-ui-datepicker' );
		
	}
	
}
?>