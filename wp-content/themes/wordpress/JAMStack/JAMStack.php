<?php 

class JAMStack {

    function __construct(){
        $this->init();
        $this->hook();
    }
    
    // Basic environment setup
    function init(){
        if(!file_exists(WP_CONTENT_DIR.'/data')){
            mkdir(WP_CONTENT_DIR.'/data');
        }

        // Auto includes all data generator classes
        $filepath = dirname(__FILE__).'/generators/';
        $files = scandir($filepath);
    
        foreach ($files as $file) {
            if (substr($file,-4,4) == '.php' && $file != 'ContentGenerator.php') {
                require_once($filepath.$file);
            }
        }
    }

    function hook(){
        add_action( 'save_post', ['JAMStack', 'save_post'], 10, 3 );
    }

    function save_post(int $post_ID, WP_Post $post, bool $update){
        $class_name = str_replace(' ', '', ucwords(str_replace('-', ' ', $post->post_type)));

        if($class_name != 'Revision'){
            $generator = new ContentGenerator($post);
            
            if( class_exists($class_name) ){
                $generator = new $class_name($post);
            }
            
            $generator->save_post();

            $list_generator = new ListGenerator();
            $list_generator->generate_json();
        }
    }
}