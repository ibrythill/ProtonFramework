<?php

	//additional logic for required fields
	if( array_key_exists('options', $options) && array_key_exists('required', $options['options']) ) {
	$options['attributes'] = 'data-check-field="protonthemes_'.( 'menu' === $options['parent'][0] ? $options['options']['required'][0].'-'.$options['parent'][1] : $options['options']['required'][0] ).'" data-check-comparison="'.$options['options']['required'][1].'" data-check-value="'.$options['options']['required'][2].'"';
	$options['classes'] .= 'hiddenFold ';
	}

    $output .= "\t".'<div class="proton-opt-box ' . $options['classes'] . '" '.( array_key_exists('attributes',$options) ? $options['attributes'] : '' ).' data-field-type="' . $options['type'] . '" data-field-name="' . $options['name'] . '">'."\n";
    $output .= "\t\t".'<div class="proton-opt-table">'."\n";
    $output .= "\t\t\t".'<div class="proton-opt-title">'.$options['title'].'</div>'."\n";
    $output .= "\t\t\t".'<div class="proton-opt-desc">'.$options['desc']."\n";
    if(array_key_exists('hint',$options)){
    	$output .= "\t\t\t\t".'<div data-tip="'.$options['hint'].'" class="data-tip-left">?<div class="tip-content">'.$options['hint'].'</div></div>'."\n";
    }
    $output .= "\t\t\t".'</div>'."\n";
    $output .= "\t\t".'</div>'."\n";
    $output .= "\t\t".'<div class="clear"></div>'."\n";
    if( array_key_exists( 'field' , $options ) ){
	    $output .= "\t\t".'<div class="proton-opt-content">'."\n";
	    $output .= "\t\t". $options['field']."\n";
	    $output .= "\t\t".'</div>'."\n";
    }
    $output .= "\t".'</div>'."\n";

