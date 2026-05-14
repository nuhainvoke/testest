<?php

// Enqueue parent theme styles
add_action('wp_enqueue_scripts', function() {
    wp_enqueue_style(
        'parent-style',
        get_template_directory_uri() . '/style.css'
    );
});

// Register Custom Post Type: Service
add_action('init', function() {
    register_post_type('service', [
        'label'        => 'Services',
        'public'       => true,
        'show_in_menu' => true,
        'supports'     => ['title', 'editor', 'thumbnail'],
        'menu_icon'    => 'dashicons-hammer',
        'has_archive'  => true,
        'rewrite'      => ['slug' => 'services'],
    ]);
});
