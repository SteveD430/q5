<?php
/**
 * The Left Sidebar for non Site pages. 
 * This Sidebar renders the Table of Contents for the page, based on the header <h> elements.
 *
 * Note: In order to retain the ToC as visible, regadless of the page contents scrolling
 * 		 we actually render the ToC twice, once as Positioned as Fixed and Visible, and a
 *		 second time as Relative and Hidden.  The reason is that Position: Fixed elements do not
 *		 partake in the document layout
 *
 * @package Q5
 * @subpackage Q5
 * @since Q5 1.0
 */

if ( ! is_front_page() && !is_category( 'Site-Page' ) ) : ?>
	<div class="q5-sidebar">
			<div id="widget-area" class="widget-area" role="complementary">
				<!-- ?php dynamic_sidebar( 'sidebar-page-left' ); ? -->
				<?php
					$post = get_post( $wp_query->post->ID );
					$toc = new q5_toc();
					$toc->build_toc(wptexturize($post->post_content));

					// TOC is rendered twice - see reason above.
					$toc->render_toc();
					$hiddenToc = array (
						'toc_class'             => 'q5_toc_hidden',
						'toc_title_class'       => 'q5_toc_title_hidden',
						'toc_entry_class'       => 'q5_toc_entry_hidden',
						'toc_heirarchy_class'   => 'q5_toc_heirarchy_hidden',
					);
					$toc->render_toc($hiddenToc);
				?>
			</div><!-- .widget-area -->
	</div><!-- .sidebar -->

<?php endif; ?>
