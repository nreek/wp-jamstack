<?php 

class Nav_menu_item extends ContentGenerator {
    public $menus = [];

    function save_post() {
        $nav_menus = wp_get_nav_menus(); 
        $menus = [];

        foreach ($nav_menus as $menu){
            $nav_items = wp_get_nav_menu_items($menu);

            $menus[] = $this->prepare_menu($menu, $nav_items);
        }

        $this->menus = $menus;
        $this->generate_json();
    }

    function prepare_menu($menu, $nav_items){
        $items = [];

        foreach($nav_items as $item){
            $items[] = [
                'ID' => $item->ID,
                'title' => $item->title,
                'permalink' => $item->url,
                'object' => $item->object,
                'type' => $item->type,
                'order' => $item->menu_order,
                'parent' => $item->menu_item_parent
            ];
        }
        
        return [
            'ID' => $menu->term_id,
            'title' => $menu->name,
            'slug' => $menu->slug,
            'items' => $items,
        ];
    }

    function generate_json(){
        $filepath = WP_CONTENT_DIR . '/data/menus.json';

        file_put_contents($filepath, json_encode($this->menus));
    }
}