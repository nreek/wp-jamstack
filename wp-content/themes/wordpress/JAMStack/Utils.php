<?php 

class Utils {
    public static function recursively_mkdir($dir){
        if(!is_dir($dir)){
            $dir_p = explode('/',$dir);
            for($a = 1 ; $a <= count($dir_p) ; $a++){
                @mkdir(implode('/',array_slice($dir_p,0,$a)));  
            }
        }
    }

    function get_image_url($size, $image_id = null, $post_id = null) {
        if (is_null($image_id)) {
            $image_id = \get_post_thumbnail_id($post_id);
        }
    
        $url =  get_stylesheet_directory_uri() . '/assets/images/img_default.png';
        $thumb = \wp_get_attachment_image_src($image_id, $size, false);
        if ($thumb) {
            $url = $thumb[0];
        }
        return $url;
    }
}