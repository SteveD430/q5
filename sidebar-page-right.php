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
				<?php echo '<span class="q5_author_title">Author: </span>';
				      echo '<span class="q5_author">' . get_the_author_meta('display_name') . '</span>'; ?>
				<?php dynamic_sidebar( 'sidebar-page-right' ); ?>
	</div><!-- .sidebar -->

<?php endif; ?>
