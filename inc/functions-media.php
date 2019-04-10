<?php

/*this function allows users to use the first image in their post as their thumbnail*/
function first_image($id = null) {
	global $post, $posts;
	$img = '';
	ob_start();
	ob_end_clean();
	if($id == null){
	$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
	}else{
		$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', get_post_field('post_content', $id), $matches);
	}
	$img = $matches [1] [0];

	return $img;
}

/*prevent from displaying images on frontpage*/
function wpi_image_content_filter($content){

    if (is_home() || is_front_page()){
      $content = preg_replace("/<img[^>]+\>/i", "", $content);
    }

    return $content;
}
//add_filter('the_content','wpi_image_content_filter',11);

/* Add lightgallery to images */
add_filter('the_content', 'proton_add_rel_attribute');
function proton_add_rel_attribute($content) {
       global $post;
       $pattern ="/<a(.*?)href=('|\")(.*?).(bmp|gif|jpeg|jpg|png)('|\")(.*?)>/i";
       $replacement = '<a$1href=$2$3.$4$5 rel="protonGallery" title="'.$post->post_title.'"$6>';
       $content = preg_replace($pattern, $replacement, $content);
       return $content;
}
//add_filter('wp_get_attachment_link', 'proton_add_gallery_rel_attribute');
function proton_add_gallery_rel_attribute($link) {
	global $post;
	return str_replace('<a href', '<a rel="protonGallery['.$post->ID.']" href', $link);
}


?>