<?php

function pro_thumbnail($args){
	$args = wp_parse_args( $args, array());
	if($args['width']!==0 && $args['height']!==0){
		$args['size'] = array(preg_replace('/px/', '', $args['width']),preg_replace('/px/', '', $args['height']));
	}

	return \Proton\Media\Image::thumbnail($args);
}

function pro_flexthumbnail($args){
	return \Proton\Media\Image::flexthumbnail($args);
}

