<?php

/*-----------------------------------------------------------------------------------*/
/* Images */
/*-----------------------------------------------------------------------------------*/



/* check even number */
function even($number)
{
	if($number%2=='1') return FALSE;
	else return TRUE;
}


//time ago
function time_ago( $type = 'post' ) {
	$d = 'comment' == $type ? 'get_comment_time' : 'get_post_time';
	return human_time_diff($d('U'), current_time('timestamp')) . " " . __('ago','proton_framework');
}



/* dodatkowe prametry w pasku adresu przy prettylinks */

/*
function add_query_vars($aVars) {
    array_push($aVars, 'data');
    return $aVars;
}

add_filter('query_vars', 'add_query_vars');

function add_rewrite_rules($aRules) {
    $aNewRules = array('repertuar/([^/]+)/?$' => '?page_id=59&data=$matches[1]');
    $aRules = $aNewRules + $aRules;
    return $aRules;
}

add_filter('rewrite_rules_array', 'add_rewrite_rules');
function getRewriteRules() {
    global $wp_rewrite; // Global WP_Rewrite class object
    return $wp_rewrite->rewrite_rules();
}
*/


/* Function add day */

function addDay($ile,$data){
$format = 'Y-m-d';
   return date ( $format, strtotime ( ''.$ile.'' . $data ) );
}

/*
eg:
addDay('+1day',$data)
*/


function czysc($zm) {
	return strip_tags(trim(($zm)));
}

?>