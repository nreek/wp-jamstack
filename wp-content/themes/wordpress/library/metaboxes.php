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
