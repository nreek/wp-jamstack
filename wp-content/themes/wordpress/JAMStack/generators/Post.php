<?php 

class Post extends ContentGenerator {
    public $post;

    function __construct($post, $request_data) {
        parent::__construct($post, $request_data);
    }

    function extend_post() {
        $this->post['meta']['author'] = 'João';
    }

    function save_post() {
        $this->extend_post();
        $this->generate_json();
    }
}