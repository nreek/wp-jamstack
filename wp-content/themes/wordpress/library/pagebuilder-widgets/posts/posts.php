<?php

/*
  Widget Name: Posts
  Description: Lista de Posts
  Author: DNA
  Author URI: https://dnaeducacaofisica.com.br
 */

namespace widgets;

class Posts extends \SiteOrigin_Widget {

    function __construct() {
        $fields = [
            'columns' => [
                'type' => 'number',
                'label' => 'Quantidade de cards por linha',
                'default' => '3'
            ],
            'show' => [
                'type' => 'select',
                'label' => 'Exibição',
                'options' => [
                    'vertical' => 'Vertical',
                    'horizontal' => 'Horizontal'
                ]
            ],
            'posts' => [
                'type' => 'posts',
                'label' => 'Conteúdos',
            ],
        ];

        parent::__construct('posts', 'Posts', [
            'panels_groups' => [WIDGETGROUP_BANNERS],
            'description' => 'Componente de Lista de Posts'
        ], [], $fields, plugin_dir_path(__FILE__));
    }

    function get_template_name($instance) {
        return 'template';
    }

    function get_style_name($instance) {
        return 'style';
    }

}

Siteorigin_widget_register('posts', __FILE__, 'widgets\Posts');
