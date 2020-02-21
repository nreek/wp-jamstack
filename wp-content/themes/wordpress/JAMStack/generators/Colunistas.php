<?php 

class Colunistas extends ContentGenerator {
    public $post;

    function __construct($post, $request_data) {
        parent::__construct($post, $request_data);
    }

    public static function extended_data ($post_id) {
        $last_post = false;

        $last_post_query = new WP_Query([
            'ppp' => 1,
            'meta_key' => 'colunista',
            'meta_value' => $post_id,
            'post_status' => 'publish'
        ]);

        foreach ( $last_post_query->posts as $post ) {
            $last_post = [
                'ID'    => $post->ID,
                'title' => $post->post_title,
                'permalink' => get_the_permalink($post->ID)
            ];
        }

        return [ 'last_post' => $last_post ];
    }
    
    function extend_post() {
        $extended_data = $this->extended_data(get_the_ID());
        
        $this->post['last_post'] = $extended_data['last_post'];
    }

    function save_post() {
        $this->extend_post();
        $this->generate_json();
    }
}