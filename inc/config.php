<?php
$git_path = '//cdn.jsdelivr.net/gh/ibrythill/ProtonFramework/assets/js/';
proton_register_script('jquery', get_home_url(). '/wp-includes/js/jquery/jquery.js', 0);
proton_register_script('LAB', 'vendor/LAB', 0);
proton_register_script('lightGallery', $git_path.'vendor/lightgallery-all.min', 0);
proton_enqueue_script('lazysizes', $git_path.'vendor/lazysizes.min', 0);
proton_register_script('backgroundcheck', $git_path.'vendor/background-check.min', 0);
proton_enqueue_script('picturefill', $git_path.'vendor/picturefill.min', 0);
proton_register_script('loadcss', $git_path.'vendor/loadCSS.min', 0);

proton_register_script('menutron', $git_path.'plugins/proton.menutron.min', 0);
proton_register_script('grid', $git_path.'plugins/proton.grid.min', 0);
proton_register_script('smoothscroll', $git_path.'plugins/proton.smoothscroll.min', 0);
proton_register_script('screenedge', $git_path.'plugins/proton.screenedge.min', 0);
proton_register_script('parallax', $git_path.'plugins/proton.parallax.min', 0);
proton_register_script('resizeend', $git_path.'plugins/proton.resizeend.min', 0);
proton_register_script('equalheight', $git_path.'plugins/proton.equalheight.min', 0);
proton_register_script('onscreen', $git_path.'plugins/proton.onscreen.min', 0);
proton_register_script('stopscroll', $git_path.'plugins/proton.stopscroll.min', 0);
proton_register_script('longtitle', $git_path.'plugins/proton.longtitle.min', 0);

proton_enqueue_script('proton_framework', $git_path.'proton.framework.min', 1);


$git_path = '//cdn.jsdelivr.net/gh/ibrythill/ProtonFramework@latest/assets/';
proton_enqueue_style( 'fontawesome', 	'https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css', 4);
proton_enqueue_style( 'lightgallery', 	$git_path . 'css/lightgallery.min.css', 5);
proton_enqueue_style( 'lg-transitions', 	$git_path . 'css/lg-transitions.min.css', 5);