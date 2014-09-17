<header class="banner" role="banner">
<!--   <div class="container"> -->
    <nav class="navbar-container" role="navigation">
      <?php
//        if (has_nav_menu('primary_navigation')) :
//          wp_nav_menu(array('theme_location' => 'primary_navigation', 'menu_class' => 'nav navbar-nav'));
//        endif;
      ?>

      <?php
			echo do_shortcode('[top-menu wwhww="what, who, how, where, when"]');
      ?>
    </nav>
<!--   </div> -->
</header>
