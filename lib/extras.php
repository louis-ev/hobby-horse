<?php
/**
 * Clean up the_excerpt()
 */
function roots_excerpt_more($more) {
  return ' &hellip; <a href="' . get_permalink() . '">' . __('Continued', 'roots') . '</a>';
}
add_filter('excerpt_more', 'roots_excerpt_more');

/**
 * Manage output of wp_title()
 */
function roots_wp_title($title) {
  if (is_feed()) {
    return $title;
  }

  $title .= get_bloginfo('name');

  return $title;
}
add_filter('wp_title', 'roots_wp_title', 10);

/**
 * Custom langage Taxonomie
 */

function langue_init() {
	register_taxonomy(
		'lang',
		'post',
		array(
			'label' => __( 'Langue' ),
			'hierarchical' => true,
			'show_ui'           => true,
			'show_admin_column' => true
		)
	);
}
add_action( 'init', 'langue_init' );

// enlever l'admin bar
add_filter( 'show_admin_bar', '__return_false' );

// dans les posts, enlever les colonnes inutiles
function gestion_colonnes( $columns ) {
  unset($columns['categories']);
  unset($columns['comments']);
  return $columns;
}
function column_init() {
  add_filter( 'manage_posts_columns' , 'gestion_colonnes');
  add_filter( 'manage_pages_columns' , 'gestion_colonnes');
}
add_action( 'admin_init' , 'column_init' );

// enlever des options de l'admin
function my_remove_menu_items() {
	remove_menu_page('edit-comments.php');
	remove_submenu_page( 'edit.php', 'edit-tags.php?taxonomy=category' );
}
add_action( 'admin_menu', 'my_remove_menu_items' );

// custom typeface
function google_font(){
	echo "<link href='http://fonts.googleapis.com/css?family=Noto+Sans|Noto+Sans:bold' rel='stylesheet' type='text/css'>","\n";
}
add_action( 'wp_enqueue_scripts', 'google_font');
