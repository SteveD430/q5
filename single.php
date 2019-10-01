<?php
get_header();
?>

	<div class="q5-container">
		<div class="q5-half-sidebar">
		</div>
		<div id="primary" class="q5-content">
			<main id="main" class="q5-siteMain">
				<div class="q5-sitePostContent">
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
				</div>
			</main>
		</div>
		<div class="q5-half-sidebar">
		</div>
	</div>
<?php
	get_footer();
?>
