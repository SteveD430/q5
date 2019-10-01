<?php
get_header(); 
?>
	<div class="q5-container">
	<?php get_sidebar('page-left'); ?>
		<div id="primary" class="q5-page-content">
				<main  id="main" class="site-main">
					<?php 
					if ( have_posts() ) 
					{
						while ( have_posts() ) 
						{
							the_post();				
							the_content();
						}
					}
					?>
				</main>
		</div>
	<?php get_sidebar('page-right'); ?>
	</div>
<?php 
	get_footer(); 
?>
