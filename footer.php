<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage Q5
 * @since 5.0.0
 */

?>
	<footer id="q5-colophon" class="q5-footer">
		<div class="q5-footerMenu">
			<?php wp_nav_menu( array(
						'theme_location' => 'footer-menu',
						'menu_class'     => 'q5-footer-menu',
					)
				);
			?>
		</div>
        <div class="q5-copyright">
		<?php echo 'Copyright © ' . get_bloginfo( 'name' ) . ' 2019'?>
		</div>
	</footer><!-- #q5-colophon -->
<?php wp_footer(); ?>

</body>
</html>
