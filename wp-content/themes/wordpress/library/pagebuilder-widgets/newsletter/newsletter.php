<?php

/*
  Widget Name: Newsletter
  Description: Newsletter
  Author: DNA
  Author URI: https://dnaeducacaofisica.com.br
 */

namespace widgets;

class Newsletter extends \SiteOrigin_Widget {

    function __construct() {
        $fields = [
            'title' => [
                'type' => 'text',
                'label' => 'Título',
            ],
            'description' => [
                'type' => 'text',
                'label' => 'Descrição',
            ],
            'success_message' => [
                'type' => 'tinymce',
                'label' => 'Mensagem de conclusão de cadastro',
            ],
            'error_message' => [
                'type' => 'tinymce',
                'label' => 'Mensagem de erro de cadastro',
            ],
        ];

        parent::__construct('newsletter', 'Newsletter', [
            'panels_groups' => [WIDGETGROUP_BANNERS],
            'description' => 'Componente de Newsletter'
        ], [], $fields, plugin_dir_path(__FILE__));
    }

    function get_template_name($instance) {
        return 'template';
    }

    function get_style_name($instance) {
        return 'style';
    }

}

Siteorigin_widget_register('newsletter', __FILE__, 'widgets\Newsletter');
