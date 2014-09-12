<?php

/**
 * array liste langue sur page d'accueil
 * [colonne-langue langue="fr, en, ru, es, ja, de, ko, pt, zh, ar, he, hi"]
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
						$chapitre = (string) rwmb_meta( 'hobby_horse_chapter' );
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

/**
 * appel de chapitre et toutes les langues associées
 * [afficher-article chapitre="8"]
 */

function afficher_article( $atts ){

	extract( shortcode_atts( array(
		'chapitre' => '',
		'partie' => '',
		'posx' => '1200',
		'posy' => '800',
	), $atts, 'afficher-article' ) );

	ob_start();

/*
		$loop = new WP_Query(
			array(
				'post_type' => 'post',
				'posts_per_page' => -1,
				'meta_key' => 'hobby_horse_chapter',
				'meta_value' => $chapitre
			)
		);
*/

		 $args = array(
			'post_type' => 'post',
			'posts_per_page' => -1,
			'meta_query' => array(
				'relation' => 'AND',
				array(
					'key' => 'hobby_horse_chapter',
					'value' => $chapitre,
				),
				array(
					'key' => 'hobby_horse_part',
					'value' => $partie,
				),
			)
		);

		$loop = new WP_Query( $args );

		if ( $loop->have_posts() ) {
			$hasPosts = true; $first = true;

	  		?>

			<section class="tableau" style="left : <?php echo $posx . 'px'; ?>; top : <?php echo $posy . 'px'; ?>;">

				<div class="tableau-cont chapter" data-chapter='<?php echo $chapitre; ?>' data-part='<?php echo $partie; ?>' >
					<?php

					$out = array();

					$taxonomy_slug = 'lang';
			        $terms = get_terms( $taxonomy_slug );

					$out[] = "<header><h4>Chapitre ".$chapitre."</h4><h4>Partie ".$partie."</h4></header>\n<ul class='lang-list'>";
					foreach ( $terms as $term ) {
					$out[] =
					  '  <a data-lang="'
					.    $term->slug.'" data-langfull="'
					.    $term->name.'"><li><abbr title="'
					.    $term->name.'">'
					.    $term->slug
					. "</abbr></li></a>\n";
					}
					$out[] = "</ul>\n";

					echo implode('', $out );

					echo "<div class='article_container'>";

					while ( $loop->have_posts() ) : $loop->the_post();

			            $terms = get_the_terms( get_the_ID(),'lang' );

						?>

					  <article <?php post_class(); ?> data-lang='<?php foreach( $terms as $term ) { echo $term->slug; } ?>' >
					    <header>
					      <h1 class="entry-title"><?php the_title(); ?></h1>
						  <?php //get_template_part('templates/entry-meta'); ?>
					    </header>
					    <div class="entry-content">
					      <?php the_content(); ?>
					    </div>
					  </article>

					<?php
					endwhile;// posts
					?>

					</div>
				</div>
			</section>

		<?php
		}

		wp_reset_postdata();

	return ob_get_clean();

}
add_shortcode( 'afficher-article', 'afficher_article' );






/**
 * appel du menu du haut : what who how where when
 * [top-menu wwhww="what, who"]
 */

function top_menu( $atts ){

	extract( shortcode_atts( array(
		'wwhww' => 'what, who, how, where, when',
	), $atts, 'top-menu' ) );

//	echo '$wwhww : ' . $wwhww;

	$wwhww = explode( ', ', $wwhww );

	ob_start();

/*
		$loop = new WP_Query(
			array(
				'post_type' => 'post',
				'posts_per_page' => -1,
				'meta_key' => 'hobby_horse_chapter',
				'meta_value' => $chapitre
			)
		);
*/



		$out = array();

		$out[] = "<div class='sommaire'><span class='icon-bar'></span><span class='icon-bar'></span><span class='icon-bar'></span></div>";


		$taxonomy_slug = 'lang';
        $terms = get_terms( $taxonomy_slug );

		$out[] = "<ul class='lang-list'>";

		foreach ( $terms as $term ) {
			$out[] =
			  '  <a data-lang="'
			.    $term->slug.'" data-langfull="'
			.    $term->name.'"><li><abbr title="'
			.    $term->name.'">'
			.    $term->slug
			. "</abbr></li></a>\n";
		}
		$out[] = "</ul>\n";

		echo implode('', $out );

		?>

		<ul class="wwhww-list">

			<?php
			for($i = 0; $i < count($wwhww); $i++) {

				 $args = array(
					'post_type' => 'wwhww',
					'posts_per_page' => -1,
					'meta_query' => array(
						array(
							'key' => 'hobby_horse_wwhww',
							'value' => $wwhww[$i],
						),
					)
				);

				$loop = new WP_Query( $args );


				if ( $loop->have_posts() ) {
					$hasPosts = true; $first = true;
			  		?>

			  		<li class="wwhww-items <?php echo $wwhww[$i]; ?>">

			  			<h3><?php echo $wwhww[$i]; ?></h3>

						<?php

						$out = array();
						?>

						<div class='wwhww-container'>

							<?php
							while ( $loop->have_posts() ) : $loop->the_post();

					            $terms = get_the_terms( get_the_ID(),'lang' );

								?>

							  <section <?php post_class(); ?> data-lang='<?php foreach( $terms as $term ) { echo $term->slug; } ?>' >
							    <header>
							      <h4 class="entry-title"><?php the_title(); ?></h4>
								  <?php //get_template_part('templates/entry-meta'); ?>
							    </header>
							    <div class="entry-content">
							      <?php the_content(); ?>
							    </div>
							  </section>

							<?php
							endwhile;// posts
							?>

						</div>
					</li>

				<?php
				}
			}
		?>

		</ul>

		<?php
		wp_reset_postdata();

	return ob_get_clean();

}
add_shortcode( 'top-menu', 'top_menu' );

