<?php

/**
 * @_STYLES
 */

define('STYLE_PATH', get_stylesheet_directory_uri() . '/stuff/styles');
define('SCRIPT_PATH', get_stylesheet_directory_uri() . '/stuff');

function custom_admin_js() {
    echo '<script type="text/javascript">$ = jQuery;</script>';
}
add_action('admin_footer', 'custom_admin_js');


add_action( 'wp_enqueue_scripts', 'asd' );
function asd() {
    wp_register_style( 'blog-main',STYLE_PATH . '/blog.css' );
    wp_enqueue_style('blog-main');
}


add_action( 'wp_enqueue_scripts', 'my_scripts_method' );
function my_scripts_method() {
    wp_enqueue_script( 'jquery_min_js', SCRIPT_PATH . '/jquery.min.js', [], '1.0', true );
}



add_filter( 'excerpt_length', function(){return 16;});

add_theme_support( 'post-thumbnails' );


add_action( 'admin_menu', 'register_my_custom_menu_page' );
function register_my_custom_menu_page(){
    add_menu_page( 'lul, first item in admin panel', 'Sasatt', 'manage_options', 'custom-lalka', 'sasatt_page_render');
}

function sasatt_page_render(){
    get_template_part('img-saver');
}