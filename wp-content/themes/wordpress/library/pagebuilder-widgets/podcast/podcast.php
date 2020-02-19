<?php

/*
  Widget Name: Banner de Podcast
  Description: Banner de Podcast
  Author: DNA
  Author URI: https://smartfit.com.br/conteudo
 */

namespace widgets;

class Podcast extends \SiteOrigin_Widget {

    function __construct() {
        $fields = [
            'title' => [
                'type' => 'text',
                'label' => 'Título',
                'default' => 'Vai que é fácil'
            ],
            'description' => [
                'type' => 'text',
                'label' => 'Descrição',
            ],
            'image' => [
                'type' => 'media',
                'label' => 'Imagem',
            ],
            'podcast_url' => [
                'type' => 'link',
                'label' => 'URL do RSS do Podcast',
                'default' => 'https://anchor.fm/s/100b9ec8/podcast/rss'
            ],
            'button_text' => [
                'type' => 'text',
                'label' => 'Texto do botão',
                'default' => 'Confira todos os programas'
            ],
            'button_url' => [
                'type' => 'link',
                'label' => 'Link do botão',
                'default' => '/podcast'
            ],
        ];

        parent::__construct('podcast', 'Banner de Podcast', [
            'panels_groups' => [WIDGETGROUP_BANNERS],
            'description' => 'Componente de Banner de Podcast'
        ], [], $fields, plugin_dir_path(__FILE__));
    }

    function get_template_name($instance) {
        return 'template';
    }

    function get_style_name($instance) {
        return 'style';
    }

}

Siteorigin_widget_register('podcast', __FILE__, 'widgets\Podcast');
