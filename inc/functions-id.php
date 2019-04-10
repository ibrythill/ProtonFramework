<?php


/*get post id outside the loop*/
function nonloopId() {
	global $wp_query;
	return $thePostID = $wp_query->post->ID;
}

/*get post name outside the loop*/
function nonloopName() {
	global $wp_query;
	return $thePostID = $wp_query->post->post_title;
}

/*get cetgory id outside the loop*/
function nonloopCatname(){
	if(is_category()){
			$cat= get_query_var('cat');
			$yourcat= get_category($cat);
			     return $yourcat->slug;
		} else{
			foreach((get_the_category()) as $category) 
        { 
		  $postcat= $category->cat_ID; 
		  $catname =$category->slug;
		  return $catname;
		} 
	}
}

/* get_page_by_title( $page_title, $output, $post_type ); ->ID */
/* this function gets page name by its id get_the_title($ID);*/
/* this function gets category name by its id get_cat_name( $cat_id ) */
/* this function gets category id by its name get_cat_ID( $cat_name ) */

function pro_get_content($post_id){
	echo apply_filters('the_content', get_post_field('post_content', $post_id));
}

if ( ! function_exists( 'cat_post_count' ) ) :

/**
 * Simple function to get category post count including all subcategories
 *
 * @link http://wordpress.stackexchange.com/a/91551 Stackexchange
 * @param  int $cat_id Category ID
 * @return int         Total post count
 */
function pro_cat_post_count( $cat_id, $taxonomy = 'category' ) {
    $q = new WP_Query( array(
        'nopaging' => true,
        'tax_query' => array(
            array(
                'taxonomy' => $taxonomy,
                'field' => 'id',
                'terms' => $cat_id,
                'include_children' => false,
            ),
        ),
        'fields' => 'ids',
    ) );
    return $q->post_count;
}

endif;

?>