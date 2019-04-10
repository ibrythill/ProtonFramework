<?php

proton_register_script('jquery', get_home_url(). '/wp-includes/js/jquery/jquery.js', 0);
proton_register_script('LAB', 'vendor/LAB', 0);
proton_register_script('lightGallery', 'vendor/lightgallery-all.min', 0);
proton_enqueue_script('lazysizes', 'vendor/lazysizes.min', 0);
proton_register_script('backgroundcheck', 'vendor/background-check.min', 0);
proton_enqueue_script('picturefill', 'vendor/picturefill.min', 0);
proton_register_script('loadcss', 'vendor/loadCSS.min', 0);

proton_register_script('menutron', 'plugins/proton.menutron.min', 0);
proton_register_script('grid', 'plugins/proton.grid.min', 0);
proton_register_script('smoothscroll', 'plugins/proton.smoothscroll.min', 0);
proton_register_script('screenedge', 'plugins/proton.screenedge.min', 0);
proton_register_script('parallax', 'plugins/proton.parallax.min', 0);
proton_register_script('resizeend', 'plugins/proton.resizeend.min', 0);
proton_register_script('equalheight', 'plugins/proton.equalheight.min', 0);
proton_register_script('onscreen', 'plugins/proton.onscreen.min', 0);
proton_register_script('stopscroll', 'plugins/proton.stopscroll.min', 0);
proton_register_script('longtitle', 'plugins/proton.longtitle.min', 0);

proton_enqueue_script('proton_framework', 'proton.framework.min', 1);



proton_enqueue_style( 'fontawesome', 	'https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css', 4);
proton_enqueue_style( 'lightgallery', 	PROTON_ASSETS_URL . 'css/lightgallery.min.css', 5);
proton_enqueue_style( 'lg-transitions', 	PROTON_ASSETS_URL . 'css/lg-transitions.min.css', 5);