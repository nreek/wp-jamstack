<?php
@ini_set( 'upload_max_size' , '64M' );
@ini_set( 'post_max_size', '64M');
@ini_set( 'max_execution_time', '300' );

add_theme_support( 'align-wide' );
add_theme_support( 'custom-logo' );

add_theme_support( 'align-wide' );
add_theme_support( 'post-thumbnails' );

function custom_menus() {
    register_nav_menu('main-menu',__( 'Menu Principal' ));
    register_nav_menu('social-networks', __( 'Redes Sociais' ));
}
add_action( 'init', 'custom_menus' );

function dd($var){
	var_dump($var);
	die;
}

require_once 'JAMStack/JAMStack.php';
require_once 'JAMStack/Utils.php';
require_once 'JAMStack/interfaces/IContentGenerator.php';
require_once 'JAMStack/generators/ContentGenerator.php';
require_once 'JAMStack/generators/ListGenerator.php';

$jamstack = new JAMStack();

