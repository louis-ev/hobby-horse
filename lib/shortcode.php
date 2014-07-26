<?php

/**
 * array liste langue sur page d'accueil
 */

function colonne_langue( $atts ){

	extract( shortcode_atts( array(
		// villes par défaut : Paris, New York, Londres
		'langue' => array( 'fr, en, zh' )
	), $atts, 'colonne-langue' ) );

	$langue = explode( ', ', $langue );
	$tax = 'lang';

	ob_start();

		for($i = 0; $i < count($langue); $i++){

			$lalangue = $langue[$i];
			$term = get_term_by('slug', $lalangue, $tax);
			$term = $term->slug;
			?>

			<section class="colonne" data-lang="<?php echo $lalangue; ?>">

				<header class="langue <?php echo $lalangue; ?>">
					<?php echo $lalangue;?>
				</header>

				<?php

				$args = array(
				    'tax_query' => array(
				        array(
						    'taxonomy' => $tax,
						    'terms' => $term,
						    'field' => 'slug',
				        )
				    ),
					'order' => 'ASC'
				);

				//  assigning variables to the loop
				$wp_query = new WP_Query($args);

				// The Loop
				while ($wp_query->have_posts()) : $wp_query->the_post();
					get_template_part('templates/content');
				endwhile;

				wp_reset_postdata();
				?>

			</section>

		<?php
		}

	return ob_get_clean();

}
add_shortcode( 'colonne-langue', 'colonne_langue' );


