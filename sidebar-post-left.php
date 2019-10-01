<?php
/**
 * The Left Post Sidebar. This Sidebar contains a heirarchical menu of all posts Ordered by Category Taxonomy then Date
 *
 * @package Q5
 * @subpackage Q5
 * @since Q5 1.0
 */

if ( ! is_front_page() && !is_category(' Site-Page' ) ) : ?>
	<div class="sidebar">

			<div id="widget-area" class="widget-area" role="complementary">
				<?php dynamic_sidebar( 'sidebar-1' ); ?>
			</div><!-- .widget-area -->

	</div><!-- .secondary -->

<?php endif; ?>
