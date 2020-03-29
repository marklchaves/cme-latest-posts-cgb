<?php

/**
 * Blocks Initializer
 *
 * Enqueue CSS/JS of all the blocks.
 *
 * @since   1.0.0
 * @package CGB
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

/**
 * Enqueue Gutenberg block assets for both frontend + backend.
 *
 * Assets enqueued:
 * 1. blocks.style.build.css - Frontend + Backend.
 * 2. blocks.build.js - Backend.
 * 3. blocks.editor.build.css - Backend.
 *
 * @uses {wp-blocks} for block type registration & related functions.
 * @uses {wp-element} for WP Element abstraction — structure of blocks.
 * @uses {wp-i18n} to internationalize the block's text.
 * @uses {wp-editor} for WP editor styles.
 * @since 1.0.0
 */
function mlc12_rock_and_roll_cgb_block_assets()
{ // phpcs:ignore
	// Register block styles for both frontend + backend.
	wp_register_style(
		'mlc12_rock_and_roll-cgb-style-css', // Handle.
		plugins_url('dist/blocks.style.build.css', dirname(__FILE__)), // Block style CSS.
		is_admin() ? array('wp-editor') : null, // Dependency to include the CSS after it.
		null // filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.style.build.css' ) // Version: File modification time.
	);

	// Register block editor script for backend.
	wp_register_script(
		'mlc12_rock_and_roll-cgb-block-js', // Handle.
		plugins_url('/dist/blocks.build.js', dirname(__FILE__)), // Block.build.js: We register the block here. Built with Webpack.
		array('wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor'), // Dependencies, defined above.
		null, // filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.build.js' ), // Version: filemtime — Gets file modification time.
		true // Enqueue the script in the footer.
	);

	// Register block editor styles for backend.
	wp_register_style(
		'mlc12_rock_and_roll-cgb-block-editor-css', // Handle.
		plugins_url('dist/blocks.editor.build.css', dirname(__FILE__)), // Block editor CSS.
		array('wp-edit-blocks'), // Dependency to include the CSS after it.
		null // filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.editor.build.css' ) // Version: File modification time.
	);

	// WP Localized globals. Use dynamic PHP stuff in JavaScript via `cgbGlobal` object.
	wp_localize_script(
		'mlc12_rock_and_roll-cgb-block-js',
		'cgbGlobal', // Array containing dynamic data for a JS Global.
		[
			'pluginDirPath' => plugin_dir_path(__DIR__),
			'pluginDirUrl'  => plugin_dir_url(__DIR__),
			// Add more data here that you want to access from `cgbGlobal` object.
		]
	);

	/**
	 * Register Gutenberg block on server-side.
	 *
	 * Register the block on server-side to ensure that the block
	 * scripts and styles for both frontend and backend are
	 * enqueued when the editor loads.
	 *
	 * @link https://wordpress.org/gutenberg/handbook/blocks/writing-your-first-block-type#enqueuing-block-scripts
	 * @since 1.16.0
	 */
	register_block_type(
		'cgb/block-mlc12-rock-and-roll',
		array(
			// Enqueue blocks.style.build.css on both frontend & backend.
			'style'         => 'mlc12_rock_and_roll-cgb-style-css',
			// Enqueue blocks.build.js in the editor only.
			'editor_script' => 'mlc12_rock_and_roll-cgb-block-js',
			// Enqueue blocks.editor.build.css in the editor only.
			'editor_style'  => 'mlc12_rock_and_roll-cgb-block-editor-css',
			'render_callback' => 'gutenberg_examples_dynamic_render_callback'
		)
	);
}

// Hook: Block assets.
add_action('init', 'mlc12_rock_and_roll_cgb_block_assets');

/**
 * Plugin Name: Gutenberg examples dynamic
 */

function gutenberg_examples_dynamic_render_callback($attributes, $content)
{
	$recent_posts = wp_get_recent_posts(array(
		'numberposts' => 1,
		'post_status' => 'publish',
	));
	if (count($recent_posts) === 0) {
		return 'No posts';
	}
	$post = $recent_posts[0];
	$post_id = $post['ID'];
	$excerpt = '';
	if (has_excerpt($post_id)) {
		$excerpt = wp_strip_all_tags(get_the_excerpt($post_id));
	}
	$read_more_button = '<div class="read-more-button-wrapper"><a class="button" href=' . esc_url( get_permalink( $post_id ) ) . '>Read More</a></div>';
	return sprintf(
		'<p>%4$s</p><h2><a href="%1$s">%2$s</a></h2><p class="post-categories">%3$s</p><p>%5$s</p><p>%6$s</p>',
		esc_url(get_permalink($post_id)),
		esc_html(get_the_title($post_id)),
		get_the_category_list(' ', '', $post_id),
		get_the_date('F j, Y', $post_id),
		$excerpt,
		$read_more_button
	);
}
