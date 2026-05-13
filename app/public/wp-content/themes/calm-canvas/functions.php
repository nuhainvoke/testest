<?php
function calm_canvas_pattern_styles()
{
	wp_enqueue_style('calm-canvas-patterns', get_stylesheet_directory_uri() . '/assets/css/patterns.css', array(), filemtime(get_stylesheet_directory() . '/assets/css/patterns.css'));
	if (is_admin()) {
		global $pagenow;
		if ('site-editor.php' === $pagenow) {
			// Do not enqueue editor style in site editor
			return;
		}
		wp_enqueue_style('calm-canvas-editor', get_stylesheet_directory_uri() . '/assets/css/editor.css', array(), filemtime(get_stylesheet_directory() . '/assets/css/editor.css'));
	}
}
add_action('enqueue_block_assets', 'calm_canvas_pattern_styles');


add_theme_support('wp-block-styles');

// Removes the default wordpress patterns
add_action('init', function () {
	remove_theme_support('core-block-patterns');
});





// Register customer Calm Canvas pattern categories
function calm_canvas_register_block_pattern_categories()
{
	register_block_pattern_category(
		'header',
		array(
			'label'       => __('Header', 'calm-canvas'),
			'description' => __('Header patterns', 'calm-canvas'),
		)
	);
	register_block_pattern_category(
		'call_to_action',
		array(
			'label'       => __('Call To Action', 'calm-canvas'),
			'description' => __('Call to action patterns', 'calm-canvas'),
		)
	);
	register_block_pattern_category(
		'content',
		array(
			'label'       => __('Content', 'calm-canvas'),
			'description' => __('Bakery and Pastry content patterns', 'calm-canvas'),
		)
	);
	register_block_pattern_category(
		'teams',
		array(
			'label'       => __('Teams', 'calm-canvas'),
			'description' => __('Team patterns', 'calm-canvas'),
		)
	);
	register_block_pattern_category(
		'banners',
		array(
			'label'       => __('Banners', 'calm-canvas'),
			'description' => __('Banner patterns', 'calm-canvas'),
		)
	);
	register_block_pattern_category(
		'contact',
		array(
			'label'       => __('Contact', 'calm-canvas'),
			'description' => __('Contact patterns', 'calm-canvas'),
		)
	);
	register_block_pattern_category(
		'layouts',
		array(
			'label'       => __('Layouts', 'calm-canvas'),
			'description' => __('layout patterns', 'calm-canvas'),
		)
	);
	register_block_pattern_category(
		'testimonials',
		array(
			'label'       => __('Testimonial', 'calm-canvas'),
			'description' => __('Testimonial and review patterns', 'calm-canvas'),
		)
	);

}

add_action('init', 'calm_canvas_register_block_pattern_categories');