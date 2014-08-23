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
				?>
					<?php
						$chapitre = (string) rwmb_meta( 'hobby_horse_radio' );
					?>
					<article class='apercu <?php echo "chapitre".$chapitre ?>' data-chapitre=<?php echo $chapitre; ?>>
					  <header>
					    <h2 class="chapitre-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
<!-- 						<?php echo rwmb_meta( 'hobby_horse_text' ); ?> -->
					  </header>
					</article>

				<?php
				endwhile;

				wp_reset_postdata();
				?>

			</section>

		<?php
		}

	return ob_get_clean();

}
add_shortcode( 'colonne-langue', 'colonne_langue' );


