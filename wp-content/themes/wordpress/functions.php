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

include __DIR__ . '/library/wp_wrapper/bootstrap.php';

require __DIR__ . '/library/images.php';
require __DIR__ . '/library/templates.php';
require __DIR__ . '/library/pagebuilder.php';
require __DIR__ . '/library/post_types.php';
require __DIR__ . '/library/metaboxes.php';
require __DIR__ . '/library/taxonomies.php';
require __DIR__ . '/library/api.php';

require_once 'JAMStack/JAMStack.php';
require_once 'JAMStack/Utils.php';
require_once 'JAMStack/interfaces/IContentGenerator.php';
require_once 'JAMStack/generators/ContentGenerator.php';
require_once 'JAMStack/generators/ListGenerator.php';

$pb_scripts = [];
$jamstack = new JAMStack();

/*
* * * Globally set page builder component's data
*/
function localize_scripts($object_name, $hash, $l10n){
    global $pb_scripts;

    foreach ( (array) $l10n as $key => $value ) {
        if ( ! is_scalar( $value ) ) {
            continue;
        }

        $l10n[ $key ] = html_entity_decode( (string) $value, ENT_QUOTES, 'UTF-8' );
    }

    $pb_scripts[$object_name][$hash] = $l10n;
}
