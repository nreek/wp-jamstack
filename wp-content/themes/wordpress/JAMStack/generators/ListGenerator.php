<?php 

class ListGenerator extends ContentGenerator {
    public $postList = [];

    function __construct() {
        $this->prepare_list();
    }

    function prepare_list() {
        $cpts = get_post_types([ 'show_in_rest' => true, 'public' => true ]);
        $cpts = array_filter($cpts, function ($cpt) {
            return $cpt != 'attachment' && $cpt != 'page'; 
        });

        $content_query = new WP_Query([
            'post_type'     => $cpts,
            'ppp'           => 100,
            'post_status'   => 'publish',
        ]);

        while ($content_query->have_posts()) {
            $content_query->the_post();
            global $post;

            $this->postList[] = $this->prepare_post( $post, [ 'content', 'tags', 'meta' ] );
        }

        \wp_reset_query();
    }

    function generate_json() {
        $filepath = WP_CONTENT_DIR.'/data/content.json';

        file_put_contents($filepath, json_encode($this->postList));
    }
}