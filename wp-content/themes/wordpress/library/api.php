<?php
add_action('rest_api_init', function () {
    register_rest_route('smart/v1', '/newsletter', array(
        'methods' => 'POST',
        'callback' => 'smart_newsletter',
    ));

    register_rest_route('smart/v1', '/archive', array(
        'methods' => 'GET',
        'callback' => 'smart_archive',
    ));

    register_rest_route('smart/v1', '/search', array(
        'methods' => 'GET',
        'callback' => 'smart_search',
    ));

    register_rest_route('smart/v1', '/related_posts', array(
        'methods' => 'GET',
        'callback' => 'smart_related_posts',
    ));

    register_rest_route('smart/v1', '/suggestion', array(
        'methods' => 'POST',
        'callback' => 'smart_suggestion',
    ));

    register_rest_route('smart/v1', '/videos/related_posts', array(
        'methods' => 'GET',
        'callback' => 'smart_videos_related_posts',
    ));
});


function smart_videos_related_posts(WP_REST_Request $request) {
    $video_id = $request->get_param('id');
    $related_posts = [];

    $query_related_posts = new WP_Query([
        'post_type' => 'post',
        'ppp' => 9,
        'post_status' => 'publish',
        'meta_key' => 'post_related_videos',
        'meta_value' => $video_id,
        'meta_compare' => 'LIKE'
    ]);

    while($query_related_posts->have_posts()) {
        $query_related_posts->the_post();
        global $post;
        
        $class_name = str_replace(' ', '', ucwords(str_replace('-', ' ', $post->post_type)));
        if ( !class_exists($class_name) ) {
            $class_name = 'ContentGenerator';
        }

        $generator = new $class_name($post, []);
        $related_posts[] = $generator->prepare_post( $post, [ 'content', 'tags', 'meta' ] );
    }

    wp_reset_postdata();

    return $related_posts;
}


function smart_related_posts(WP_REST_Request $request) {
    $posts_ids = $request->get_param('posts');
    $related_posts = [];

    $related_posts_query = new WP_Query([
        'post__in' => $posts_ids,
        'post_status' => 'publish',
    ]);

    while($related_posts_query->have_posts()) {
        $related_posts_query->the_post();
        global $post;
        
        $class_name = str_replace(' ', '', ucwords(str_replace('-', ' ', $post->post_type)));
        if ( !class_exists($class_name) ) {
            $class_name = 'ContentGenerator';
        }

        $generator = new $class_name($post, []);
        $related_posts[] = $generator->prepare_post( $post, [ 'content', 'tags', 'meta' ] );
    }

    wp_reset_postdata();

    return $related_posts;
}


function smart_add_custom_endpoint( $allowed_endpoints ) {
    if ( ! isset( $allowed_endpoints[ 'smart/v1' ] ) || ! in_array( 'archive', $allowed_endpoints[ 'smart/v1' ] ) ) {
        $allowed_endpoints[ 'smart/v1' ][] = 'archive';
    }
    return $allowed_endpoints;
}
add_filter( 'wp_rest_cache/allowed_endpoints', 'smart_add_custom_endpoint', 10, 1);

function smart_newsletter(WP_REST_Request $request) {
    $email = $request->get_param( 'email' );
    $editorials = $request->get_param( 'editorials' );
    
    if( !empty($email) ) {
        $lead = wp_insert_post([
            'post_title' => $email,
            'post_type'  => 'newsletter',
            'post_status' => 'publish'
        ]);

        add_post_meta($lead, 'editorials', join(', ', $editorials));
    }

    if ( !isset($lead) || $lead == '0' ) {
        return new WP_Error( 'register_failed', 'Falha ao cadastrar', array( 'status' => 500 ) );
    }

    return [ 'post' => $lead ];
}


function smart_suggestion(WP_REST_Request $request) {
    $parent_id = $request->get_param( 'parent_id' );
    $message = $request->get_param( 'message' );
    $helpful = $request->get_param( 'helpful' );
    
    if($helpful == 'yes') {
        $helpful_qtd = get_post_meta($parent_id, 'helpful', true);
        update_post_meta($parent_id, 'helpful', ($helpful_qtd+1) );
        return [ 'post' => $parent_id ];
    } 


    if( !empty($parent_id) ) {
        $post = wp_insert_post([
            'post_title' => $message,
            'post_type'  => 'suggestion',
            'post_status' => 'publish'
        ]);

        add_post_meta($post, 'parent_id', $parent_id);
    }

    if ( !isset($post) || $post == '0' ) {
        return new WP_Error( 'register_failed', 'Falha ao cadastrar', array( 'status' => 500 ) );
    }

    return [ 'post' => $post ];
}

function smart_archive( WP_REST_Request $request ) {
    $term_slug = $request->get_param('term');

    $term = get_terms([ 
        'get'                    => 'all',
        'number'                 => 1,
        'update_term_meta_cache' => false,
        'orderby'                => 'none',
        'suppress_filter'        => true,
        'slug' => $term_slug
    ]);

    if( count($term) > 0 ) {
        $term = $term[0];
    }

    $relations = [];

    $terms_query = new WP_Query( [
        'ppp' => '40',
        'post_status' => 'publish',
        'tax_query' => array(
            array(
                'taxonomy' => $term->taxonomy,
                'field'    => 'term_id',
                'terms'    => $term->term_id,
            ),
        ),
    ] );

    while($terms_query->have_posts()) {
        $terms_query->the_post();
        global $post;
        
        $class_name = str_replace(' ', '', ucwords(str_replace('-', ' ', $post->post_type)));
        if ( !class_exists($class_name) ) {
            $class_name = 'ContentGenerator'; 
        }

        $generator = new $class_name($post, []);
        $relations[] = $generator->prepare_post( $post, [ 'content', 'tags', 'meta' ] );
    }

    \wp_reset_query();

    return [
        'ID' => $term->term_id,
        'title' => $term->name,
        'excerpt' => $term->description,
        'slug' => $term->slug,
        'order' => $term->order,
        'parent' => $term->parent,
        'type' => $term->taxonomy,
        'relations' => $relations,
    ];
}

function smart_search( WP_REST_Request $request ) {
    $s = $request->get_param('s');
    $posts = [];

    $s_query = new WP_Query([
        'post_status' => 'publish',
        's' => $s,
    ]);


    while($s_query->have_posts()) {
        $s_query->the_post();
        global $post;
        
        $class_name = str_replace(' ', '', ucwords(str_replace('-', ' ', $post->post_type)));
        if ( !class_exists($class_name) ) {
            $class_name = 'ContentGenerator';
        }

        $generator = new $class_name($post, []);
        $related_posts[] = $generator->prepare_post( $post, [ 'content', 'tags', 'meta' ] );
    }

    wp_reset_postdata();

    return $related_posts;


}