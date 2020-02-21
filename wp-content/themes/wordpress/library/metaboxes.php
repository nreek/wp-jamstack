<?php
add_action('cmb2_admin_init', function () {
    
    $cmb_post = new_cmb2_box(array(
        'id' => 'post_metabox',
        'title' => "Opções de Post",
        'object_types' => array('post'), // Post type
        'context' => 'normal',
        'priority' => 'high',
        'show_names' => true,
    ));

    $cmb_post->add_field(array(
        'name' => 'Autor',
        'id' => 'author',
        'defaul' => 'Redação',
        'type' => 'text'
    ));

    $colunistas = ['' => 'Nenhum'];
    $colunistas_query = new WP_Query([
        'post_type' => 'colunistas',
        'ppp'       => -1,
        'post_status' => 'publish'
    ]);
    
    while($colunistas_query->have_posts()) {
        $colunistas_query->the_post();
        $colunistas[get_the_ID()] = get_the_title();
    }
    wp_reset_postdata();

    $cmb_post->add_field(array(
        'name' => 'Colunista',
        'id' => 'colunista',
        'type' => 'select',
        'options' => $colunistas
    ));

    $cmb_newsletter = new_cmb2_box(array(
        'id' => 'newsletter_metabox',
        'title' => "Informações do cadastro",
        'object_types' => array('newsletter'), // Post type
        'context' => 'normal',
        'priority' => 'high',
        'show_names' => true,
    ));

    $cmb_newsletter->add_field(array(
        'name' => 'E-mail',
        'id' => 'email',
        'type' => 'text'
    ));

    $cmb_newsletter->add_field(array(
        'name' => 'Whatsapp',
        'id' => 'whatsapp',
        'type' => 'text'
    ));
});
