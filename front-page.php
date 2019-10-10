<?php
/**
 * The front page template file
 * Displays the Sites Front Page including Central Menu.
 *
 * @package Quintic
 * @subpackage q5
 * @since 1.0
 * @version 1.0
 */

get_header(); ?>
<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
        <img id="mainBodyImage" />
		<script>
            var image = document.getElementById("mainBodyImage");
            image.src = '<?php if (get_theme_mod( 'q5_setting_site_page_image' )) : echo get_theme_mod( 'q5_setting_site_page_image'); else: echo get_site_url().'/wp-content/themes/q5/img/q5_background.jpg'; endif; ?>';
        </script>

		<div id="mainBodyMenuBar">
			<?php wp_nav_menu( array(
						'theme_location' => 'frontpage-menu',
						'menu_class' => 'q5-frontpage-menu',
					)
				);
			?>
		</div><!-- mainBodyMenuBar -->
	</main><!-- #main -->

</div><!-- #primary -->

<?php
get_footer();
?>
