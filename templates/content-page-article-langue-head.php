<?php

for($i = 1; $i <= 9; $i++){

	// pour chaque chapire
	$loop = new WP_Query(

		array(
			'post_type' => 'post',
			'posts_per_page' => -1,
			'meta_key' => 'hobby_horse_radio',
			'meta_value' => $i
		)
	);

	if ( $loop->have_posts() ) {
		$hasPosts = true; $first = true;

  		?>

		<section class='chapter' data-chapter='<?php echo $i; ?>'>

			<?php

			$out = array();

			$taxonomy_slug = 'lang';
	        $terms = get_terms( $taxonomy_slug );

			$out[] = "<header><h4>Chapitre ".$i."</h4></header>\n<ul class='lang-list'>";
			foreach ( $terms as $term ) {
			$out[] =
			  '  <a data-lang="'
			.    $term->slug.'"><li>'
			.    $term->slug
			. "</li></a>\n";
			}
			$out[] = "</ul>\n";

			echo implode('', $out );

			echo "<div class='article_container'>";

			while ( $loop->have_posts() ) : $loop->the_post();

	            $terms = get_the_terms( $post->ID,'lang' );

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

		</section>
	<?php
		wp_reset_postdata();
	}

}