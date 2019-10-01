<?php
/* Template Name: Archive Page Custom */
get_header(); 
?>

<!-- / END HOME SECTION -->
<div id="content" class="site-content">

	<div class="container">
	<p>Single Products</p>
		<div class="q5-LeftSidebar">
			<?php get_sidebar(); ?>
		</div>
		<div class="q5-Content">
			<div id="primary" class="content-area">
				<main id="main" class="site-main" role="main">
					<?php 
					while ( have_posts() ) : 
						the_post(); // standard WordPress loop. ?>
						<p class="q5-PageTitle"> <?php the_title(); ?> </p>
						<div class="q5-PageContent"> <?php the_content(); ?> </div> 
					<?php endwhile; // end of the loop. ?>
				</main><!-- #main -->
			</div><!-- #primary -->
		</div>
	</div><!-- .container -->
</div>
<?php get_footer(); ?>
