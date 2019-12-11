<?php 

class Post extends ContentGenerator {
    public $post;

    function __construct($post) {
        parent::__construct($post);
    }

    function extend_post() {
        $this->post['meta']['author'] = 'João';
    }

    function save_post() {
        $this->extend_post();
        $this->generate_json();
    }
}