<?php
/**
 * The front page template file
 *
 * If the user has selected a static page for their homepage, this is what will
 * appear.
 * Learn more: https://codex.wordpress.org/Template_Hierarchy
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
            image.src = '<?php if (get_theme_mod( 'q5_setting_site_page_image' )) : echo get_theme_mod( 'q5_setting_site_page_image'); else: echo get_template_directory_uri().'/img/q5_background.jpg'; endif; ?>';
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
