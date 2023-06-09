<?php

// === >>>> Gift Card Image custom post type & Category Reg. <<<< === \\

add_action('init', 'gift_card_custom_post_for_img');

function gift_card_custom_post_for_img() {

  $labels = [
    "name"          => esc_html__("Gift Card Images", "gtw"),
    "singular_name" => esc_html__("Gift Card Images", "gtw"),
    "menu_name"     => esc_html__("Gift To Wallet", "gtw"),
    "all_items"     => esc_html__("Gift Card Images", "gtw"),
    "add_new"       => esc_html__("Add Image", "gtw"),
    "edit_item"     => esc_html__("Edit Image", "gtw"),
    "add_new_item"  => esc_html__("Add New Image", "gtw"),
  ];

  $args = [
    "label"           => esc_html__("Gift Card Image", "gtw"),
    "labels"          => $labels,
    // "description" => "",
    "public"          => true,
    "show_ui"         => true,
    "show_in_rest"    => true,
    "rest_base"       => "",
    "rest_namespace"  => "wp/v2",
    "has_archive"     => false,
    "show_in_menu"    => true,
    "show_in_nav_menus"=> true,
    "delete_with_user"=> false,
    "capability_type" => "post",
    "map_meta_cap"    => true,
    "hierarchical"    => true,
    "can_export"      => false,
    "rewrite"         => ["slug" => "gift_card", "with_front" => true],
    "query_var"       => true,
    "menu_position"   => 5,
    // "menu_icon"    => "dashicons-images-alt2", // menu icon
    "menu_icon"       => plugin_dir_url(__FILE__).'assets/images/wallet.png', // icon url
    "supports"        => ["title", "thumbnail"], // "editor", 'excerpt'
    "taxonomies"      => ["gift_card_category"],
    "show_in_graphql" => false,
    "exclude_from_search"   => false,
    "publicly_queryable"    => true,
    "rest_controller_class" => "WP_REST_Posts_Controller",
  ];

  register_post_type("gift_card", $args);
  //flush_rewrite_rules();

  $labels = array(
    'name'          => _x('Gift Card Image Categories / Tabs', 'gtw'),
    'singular_name' => _x('Gift Card Image Category', 'gtw'),
    'search_items'  => __('Search Category', 'gtw'),
    'popular_items' => __('Popular Category', 'gtw'),
    'all_items'     => __('All Category', 'gtw'),
    'parent_item'   => null,
    'parent_item_colon' => null,
    'edit_item'     => __('Edit Category', 'gtw'),
    'update_item'   => __('Update Category', 'gtw'),
    'add_new_item'  => __('Add New Category / Tab', 'gtw'),
    'new_item_name' => __('New Category Name', 'gtw'),
    'menu_name'     => __('Category / Tabs', 'gtw'),
    'separate_items_with_commas' => __('Separate category with commas', 'gtw'),
    'add_or_remove_items'        => __('Add or remove categories'),
    'choose_from_most_used'      => __('Choose from the most used categories', 'gtw'),
  );



  register_taxonomy('gift_card_category', 'gift_card', array(
    'hierarchical' => true,
    'labels'       => $labels,
    'show_ui'      => true,
    'show_in_rest' => true,
    'query_var'    => true,
    'rewrite'      => array('slug' => 'gift_card-category'),
    'show_admin_column'     => true,
    'update_count_callback' => '_update_post_term_count',
  ));

}

