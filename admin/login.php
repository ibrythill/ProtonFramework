<?php

function custom_login_css() {
echo '<link rel="stylesheet" type="text/css" href="'.get_stylesheet_directory_uri().'/assets/login/login-styles.css" />';
}

add_action('login_head', 'custom_login_css');

function custom_fonts() {
echo '<link href="http://fonts.googleapis.com/css?family=Open+Sans+Condensed:300,700" rel="stylesheet" type="text/css" />';
}

add_action('login_head', 'custom_fonts');

add_action( 'login_head', 'untame_fadein',30);

function untame_fadein() {
echo '<script type="text/javascript">// <![CDATA[
jQuery(document).ready(function() { jQuery("#loginform,#nav,#backtoblog").css("display", "none");          jQuery("#loginform,#nav,#backtoblog").fadeIn(3500);     
});
// ]]></script>';
}

?>