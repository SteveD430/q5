<?php
/**
 * The Right Page Sidebar. This Sidebar contains any asides that have been added to the Page being displayed
 *
 * @package Q5
 * @subpackage Q5
 * @since Q5 1.0
 */

if ( ! is_front_page() && !is_category( 'Site-Page' ) ) : ?>
	<div class="q5-sidebarPageRight">
				<?php dynamic_sidebar( 'sidebar-page-right' ); ?>
	</div><!-- .sidebar -->

<?php endif; ?>
