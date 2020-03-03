<?php 

class Post extends ContentGenerator {
    public $post;

    function __construct($post, $request_data) {
        parent::__construct($post, $request_data);
    }

    public static function extended_data ($post_id) {
        $author = get_post_meta($post_id, 'author', true);

        return [ 'author' => $author ];
    }
    
    function extend_post() {
        $extended_data = $this->extended_data(get_the_ID());
        
        $this->post['author'] = $extended_data['author'];
        $this->post['related_posts'] = get_post_meta(get_the_ID(), 'related_posts', true);
    }

    function save_post() {
        $this->extend_post();
        $this->generate_json();
    }
}