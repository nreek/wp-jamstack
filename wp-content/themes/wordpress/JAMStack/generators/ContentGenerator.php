<?php 

class ContentGenerator implements IContentGenerator{
    public $post;

    function __construct($post) {
        $this->post = $this->prepare_post($post);
    }

    function prepare_post( $post, $except = [] ) {
        $content = get_the_content( null, false, $post->ID );
        $content = apply_filters( 'the_content', $content );
        $content = str_replace( ']]>', ']]&gt;', $content );
            
        $excerpt = has_excerpt( $post->ID ) ? get_the_excerpt( $post->ID ) : '';

        // Exclude unuseful meta data such as "_ping" and etc, which conventionaly starts with a _
        $post_metas = get_post_meta($post->ID);
        $meta = [];
        foreach( $post_metas as $key => $value ) {
            if( $key[0] != '_' ) {
                $meta[$key] = $value;
            }
        }
        
        $post = [
            'ID'        => $post->ID,
            'title'     => $post->post_title,
            'excerpt'   => $excerpt,
            'slug'      => $post->post_name,
            'thumbnail' => Utils::get_image_url( 'full', null, $post->ID ),
            'meta'      => $meta
        ];

        if( !in_array( 'content', $except ) ) {
            $this->post['content'] = $content;
        }

        if( !in_array( 'meta', $except ) ) {
            $this->post['meta'] = $meta;
        }

        return $post;
    }

    function generate_json() {
        $filepath = $this->get_dir_path($this->post['slug']);
        
        file_put_contents($filepath, json_encode($this->post));
    }

    function save_post() {
        $this->generate_json();
    }

    function get_dir_path( $slug, $extension = 'json' ) {
        if(empty($slug) || strlen($slug) < 2){
            $slug = 'ab';
        }
        
        $directories = [
            WP_CONTENT_DIR,
            'data',
            ord($slug[0]),
            ord($slug[1]),
        ];

        $dir_path = implode('/', $directories);
        Utils::recursively_mkdir($dir_path);

        return $dir_path . '/' . $slug . '.' . $extension;
    }
}