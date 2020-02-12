<?php

class BlogGenerator extends ContentGenerator {
    public $blog;

    function __construct() {

        $this->prepare_blog();
    }

    function prepare_blog() {
        $homepage_url = get_post_field( 'post_name', (get_option('page_on_front')) );
        $homepage_json = ord($homepage_url[0]) .'/'.ord($homepage_url[1]).'/'.$homepage_url.'.json';

        $this->blog = [
            'logo' => get_custom_logo(),
            'home' => '',
            'title' => get_bloginfo('title'),
            'description' => get_bloginfo('description'),
            'homepage_json' => $homepage_json
        ];
    }

    function generate_json() {
        $filepath = WP_CONTENT_DIR . '/data/blog.json';
        file_put_contents($filepath, json_encode($this->blog));
    }
}