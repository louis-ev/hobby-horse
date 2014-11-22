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
		array('post', 'wwhww'),
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

// ajouter le post type éléments du menu
add_action( 'init', 'create_post_type' );
function create_post_type() {
  register_post_type( 'wwhww',
    array(
      'labels' => array(
        'name' => __( 'What Who How Where When' )
      ),
      'public' => true,
    )
  );
}





function add_css(){
?>

<style>
#adminmenu .menu-icon-portfolio div.wp-menu-image:before {
  content: "\f232";
}
#adminmenu .menu-icon-publications div.wp-menu-image:before {
	content: "\f135";
}
#adminmenu .menu-icon-membres div.wp-menu-image:before {
	content: "\f307";
}
table.widefat .column-title{
	width: 200px;
}
table.widefat .column-riv_post_auteur{
	width: 100px;
}
table.widefat .column-riv_post_chapitre{
	width: 70px;
}
table.widefat .column-riv_post_partie{
	width: 70px;
}

</style>

<?php
}
add_action( 'admin_head', 'add_css' );


function posts_columns($defaults){
	  unset($defaults['date']);
	  unset($defaults['author']);
    $defaults['riv_post_auteur'] = __('Auteur');
    $defaults['riv_post_categories'] = __('Catégories');
    $defaults['riv_post_chapitre'] = __('Chapitre');
    $defaults['riv_post_partie'] = __('Partie');
    return $defaults;
}
function custom_columns($column_name, $id){
    if($column_name === 'riv_post_auteur'){
			echo $auteur = rwmb_meta( 'hobby_horse_text' );
    }
    if($column_name === 'riv_post_categories'){
	    $terms = get_the_term_list( $id , 'category' , '' , ', ' , '' );
      if ( is_string( $terms ) ) {
		    echo $terms;
	    }	else {
			    _e( 'Pas de catégorie(s) trouvées.', 'hobbyhorse' );
		  }
		}
    if($column_name === 'riv_post_chapitre'){
			echo $chapitre = rwmb_meta( 'hobby_horse_chapter' );
    }
    if($column_name === 'riv_post_partie'){
			echo $part = rwmb_meta( 'hobby_horse_part' );
    }
}
// ... pour les portfolio
add_filter('manage_edit-post_columns', 'posts_columns', 5);
add_action( 'manage_posts_custom_column' , 'custom_columns', 10, 2 );

// supprimer des colonnes pour les articles
add_action( 'init', 'post_remove_featured' );
function post_remove_featured() {
	remove_post_type_support( 'post', 'thumbnail' );
	remove_post_type_support( 'post', 'excerpt' );
	remove_post_type_support( 'post', 'trackbacks' );
	remove_post_type_support( 'post', 'revisions' );
	remove_post_type_support( 'post', 'comments' );
	remove_post_type_support( 'post', 'post-formats' );
	remove_post_type_support( 'post', 'custom-fields' );
}




