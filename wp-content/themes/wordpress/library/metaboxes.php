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

    $cmb_post_side = new_cmb2_box(array(
        'id' => 'post_metabox_side',
        'title' => "Infos de Post",
        'object_types' => array('post'), // Post type
        'context' => 'side',
        'priority' => 'low',
        'show_names' => true,
    ));

    $cmb_post->add_field(array(
        'name' => 'Autor',
        'id' => 'author',
        'defaul' => 'Redação',
        'type' => 'text'
    ));

    $cmb_post_side->add_field(array(
        'name' => 'Foi de ajuda?',
        'id' => 'helpful',
        'defaul' => '0',
        'type' => 'text'
    ));

    $colunistas = ['' => 'Nenhum'];
    // $colunistas_query = new WP_Query([
    //     'post_type' => 'colunistas',
    //     'ppp'       => -1,
    //     'post_status' => 'publish'
    // ]);
    
    // while($colunistas_query->have_posts()) {
    //     $colunistas_query->the_post();
    //     $colunistas[get_the_ID()] = get_the_title();
    // }
    // wp_reset_postdata();
    // wp_reset_query();

    $cmb_post->add_field(array(
        'name' => 'Colunista',
        'id' => 'colunista',
        'type' => 'select',
        'options' => $colunistas
    ));

    $cmb_post->add_field(array(
        'name' => 'Colunista',
        'id' => 'colunista',
        'type' => 'select',
        'options' => $colunistas
    ));


    $cmb_post_related = $cmb_post->add_field( array(
        'id'          => 'post_related_videos',
        'type'        => 'group',
        'name' => __( 'Vídeos Relacionados', 'cmb2' ),
        'repeatable'  => true, // use false if you want non-repeatable group
        'options'     => array(
            'group_title'       => __( 'Vídeo {#}', 'cmb2' ), // since version 1.1.4, {#} gets replaced by row number
            'add_button'        => __( 'Adicionar outro Vídeo', 'cmb2' ),
            'remove_button'     => __( 'Remover Vídeo', 'cmb2' ),
            'sortable'          => true,
        ),
    ) );

    $cmb_post->add_group_field($cmb_post_related, array(
        'name' => 'ID do vídeo relacionado',
        'id' => 'post_related_video',
        'type' => 'text'
    ));

    if( isset($_GET['post']) ) {
        $related_posts_meta = get_post_meta($_GET['post'], 'related_posts', true);
        
        if ( empty($related_posts_meta) || count($related_posts_meta) == 0 ) {
            $tags = wp_get_post_tags($_GET['post']);
            if ($tags) {
                $tag_ids = array();
                foreach($tags as $individual_tag) $tag_ids[] = $individual_tag->term_id;
                $args=array(
                    'tag__in' => $tag_ids,
                    'post__not_in' => array($_GET['post']),
                    'posts_per_page'=> 4, // Number of related posts to display.
                    'caller_get_posts'=>1
                );
            
                $my_query = new wp_query( $args );
                $related_posts = [];
                while( $my_query->have_posts() ) {
                    $my_query->the_post();

                    $related_posts[] = get_the_ID();
                }

                add_post_meta( $_GET['post'], 'related_posts', $related_posts );

                wp_reset_postdata();
                wp_reset_query();
            }
        }

        $cmb_post->add_field( array(
            'name'    => __( 'Posts Relacionados', 'yourtextdomain' ),
            'id'      => 'related_posts',
            'type'    => 'custom_attached_posts',
            'column'  => true, // Output in the admin post-listing as a custom column. https://github.com/CMB2/CMB2/wiki/Field-Parameters#column
            'options' => array(
                'show_thumbnails' => false, // Show thumbnails on the left
                'filter_boxes'    => true, // Show a text box for filtering the results
                'query_args'      => array(
                    'posts_per_page' => 30,
                    'post_type'      => 'post',
                ), // override the get_posts args
            ),
        ) );
    }

    $cmb_newsletter = new_cmb2_box(array(
        'id' => 'newsletter_metabox',
        'title' => "Informações do cadastro",
        'object_types' => array('newsletter'), // Post type
        'context' => 'normal',
        'priority' => 'high',
        'show_names' => true,
    ));

    $cmb_newsletter->add_field(array(
        'name' => 'Editorias',
        'id' => 'editorials',
        'type' => 'text'
    ));

    $cmb_suggestion = new_cmb2_box(array(
        'id' => 'suggestion_metabox',
        'title' => "Informações do cadastro",
        'object_types' => array('suggestion'), // Post type
        'context' => 'normal',
        'priority' => 'high',
        'show_names' => true,
    ));

    $cmb_suggestion->add_field(array(
        'name' => 'Post',
        'id' => 'parent_id',
        'type' => 'text'
    ));
});
