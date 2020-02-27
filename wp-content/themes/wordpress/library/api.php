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
});

function smart_add_custom_endpoint( $allowed_endpoints ) {
    if ( ! isset( $allowed_endpoints[ 'smart/v1' ] ) || ! in_array( 'archive', $allowed_endpoints[ 'smart/v1' ] ) ) {
        $allowed_endpoints[ 'smart/v1' ][] = 'archive';
    }
    return $allowed_endpoints;
}
add_filter( 'wp_rest_cache/allowed_endpoints', 'smart_add_custom_endpoint', 10, 1);

function smart_newsletter(WP_REST_Request $request) {
    $name = $request->get_param( 'name' );
    $email = $request->get_param( 'email' );
    $whatsapp = $request->get_param( 'whatsapp' );
    
    if(!empty($name) && !empty($email) && !empty($whatsapp) ) {
        $lead = wp_insert_post([
            'post_title' => $name,
            'post_type'  => 'newsletter',
            'post_status' => 'publish'
        ]);

        add_post_meta($lead, 'email', $email, true);
        add_post_meta($lead, 'whatsapp', $whatsapp, true);
    }

    if ( !isset($lead) || $lead == '0' ) {
        return new WP_Error( 'register_failed', 'Falha ao cadastrar', array( 'status' => 500 ) );
    }

    return [ 'post' => $lead ];
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
  