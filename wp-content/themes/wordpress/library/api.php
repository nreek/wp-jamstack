<?php
add_action('rest_api_init', function () {
    register_rest_route('dna/v1', '/newsletter', array(
        'methods' => 'POST',
        'callback' => 'smart_newsletter',
    ));
});

function smart_newsletter(WP_REST_Request $request) {
    $name = $param = $request->get_param( 'name' );
    $email = $param = $request->get_param( 'email' );
    $whatsapp = $param = $request->get_param( 'whatsapp' );
    
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
  