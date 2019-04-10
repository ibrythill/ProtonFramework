<?php

/*-----------------------------------------------------------------------------------*/
/* limits */
/*-----------------------------------------------------------------------------------*/
/* create excerpt*/
function string_limit_words($string, $word_limit) {
     $words = explode(' ', $string);
     $wroc = implode(' ', array_slice($words, 0, $word_limit));
	 $wroc .= ' [...]';
	 return strip_tags($wroc);
   }

function excerpt($limit) {
  $excerpt = explode(' ', get_the_excerpt(), $limit);
  if (count($excerpt)>=$limit) {
    array_pop($excerpt);
    $excerpt = implode(" ",$excerpt).'...';
  } else {
    $excerpt = implode(" ",$excerpt);
  }	
  $excerpt = preg_replace('`\[[^\]]*\]`','',$excerpt);
  return $excerpt;
} 

function content($limit) {
  $excerpt = explode(' ', get_the_content(), $limit);
  if (count($excerpt)>=$limit) {
    array_pop($excerpt);
    $excerpt = implode(" ",$excerpt).'...';
  } else {
    $excerpt = implode(" ",$excerpt);
  }	
  $excerpt = preg_replace('`\[[^\]]*\]`','',$excerpt);
  return apply_filters('the_content', strip_tags($excerpt));
} 


function split_content($post_id,$part) {

	global $more;
	$more = true;
	$content = preg_split('/<!--more-->/i', get_post_field('post_content', $post_id));
	for($c = 0, $csize = count($content); $c < $csize; $c++) {
		$content[$c] = apply_filters('the_content', $content[$c]);
	}
	return $content[$part];

}




/*
Plugin Name: Limit Posts
Plugin URI: http://labitacora.net/comunBlog/limit-post.phps
Description: Limits the displayed text length on the index page entries and generates a link to a page to read the full content if its bigger than the selected maximum length.
Usage: the_content_limit($max_charaters, $more_link)
Version: 1.1
Author: Alfonso Sánchez-Paus Díaz y Julián Simón de Castro
Author URI: http://labitacora.net/
License: GPL
Download URL: http://labitacora.net/comunBlog/limit-post.phps
Make:
    In file index.php
    replace the_content()
    with the_content_limit(1000, "more")
*/

function the_content_limit($max_char, $more_link_text = 'Read&rarr;', $stripteaser = 0, $more_file = '') {
    $content = get_the_content($more_link_text, $stripteaser, $more_file);
    $content = apply_filters('the_content', $content);
    $content = str_replace(']]>', ']]&gt;', $content);
    $content = strip_tags($content,'');

   if (strlen($_GET['p']) > 0) {
      echo "<p>";
      echo $content;
      echo "&nbsp;<a href='";
      the_permalink();
      echo "'>"."Read More &rarr;</a>";
      echo "</p>";
   }
   else if ((strlen($content)>$max_char) && ($espacio = strpos($content, " ", $max_char ))) {
        $content = substr($content, 0, $espacio);
        $content = $content;
        echo "<p>";
        echo $content;
        echo "...";
        echo "&nbsp;<a href='";
        the_permalink();
        echo "'>".$more_link_text."</a>";
        echo "</p>";
   }
   else {
      echo "<p>";
      echo $content;
      echo "&nbsp;<a href='";
      the_permalink();
      echo "'>"."Read More &rarr;</a>";
      echo "</p>";
   }
}

?>