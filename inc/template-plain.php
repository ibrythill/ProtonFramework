<?php

	$output .= "\t". '<div class="proton-multi-field" data-field-type="'.$options['type'].'"';
	if( array_key_exists( 'options' , $options ) && array_key_exists( 'width' , $options['options'] ) ){
		$output .= "\t". 'style="width:'.$options['options']['width'].'%; padding: 0 10px;"';
	}else{
		$output .= "\t". 'style="width:100%; padding: 0 ;"';
	}
	$output .= "\t". '>'."\n";
    if( array_key_exists( 'field' , $options ) ){
	    $output .= "\t\t". $options['field']."\n";
    }


	    $output .= "\t". '</div>'."\n";

