<?php
/* Template Name: Category Site Page */
get_header(); 
?>

<!-- / END HOME SECTION -->
<div id="content" class="q5-container">
		<div class="q5-content">
				<main id="main" class="site-main" role="main">
				      <img id="mainBodyImageTransparent" />
					<script>
						var image = document.getElementById("mainBodyImageTransparent");
						image.src = '<?php if (get_theme_mod( 'q5_setting_site_page_image' )) : echo get_theme_mod( 'q5_setting_site_page_image'); else: echo get_template_directory_uri().'/img/q5_background.jpg'; endif; ?>';
					</script>
					<?php 
					while ( have_posts() ) : 
						the_post();  // Standard WordPress loop. ?>
						<div class="q5-sitePageContent" min-width="40%">						
							<?php the_content(); ?>
						</div> 
					<?php endwhile; // end of the loop. ?>
				</main><!-- #main -->
		</div>
</div><!-- .container -->

<?php 
	get_footer(); 
?>
