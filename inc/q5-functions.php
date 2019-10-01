<?php
if( ! function_exists( 'q5_dropdown_posts' ) ) {
	/**
	 * Dropdown HTML menu of specified Post type.
	 *
	 * The 'exclude' argument allows specific posts to be excluded.
	 *
	 * Supports all WP_Query arguments
	 * @see https://codex.wordpress.org/Class_Reference/WP_Query
	 *
	 * The available arguments are as follows:
	 *
	 * @param array|string $args {
	 *     Optional. Array or string of arguments to generate a drop-down of posts.
	 *     {@see WP_Query for additional available arguments.
	 *
	 *     @type string       $show_option_all         Text to show as the drop-down default (all).
	 *                                                 Default empty.
	 *     @type string       $show_option_none        Text to show as the drop-down default when no
	 *                                                 posts were found. Default empty.
	 *     @type int|string   $option_none_value       Value to use for $show_option_non when no posts
	 *                                                 were found. Default -1.
	 *     @type array|string $show_callback           Function or method to filter display value (label)
	 *
	 *     @type string       $orderby                 Field to order found posts by.
	 *                                                 Default 'post_title'.
	 *     @type string       $order                   Whether to order posts in ascending or descending
	 *                                                 order. Accepts 'ASC' (ascending) or 'DESC' (descending).
	 *                                                 Default 'ASC'.
	 *     @type array|string $include                 Array or comma-separated list of post IDs to include.
	 *                                                 Default empty.
	 *     @type array|string $exclude                 Array or comma-separated list of post IDs to exclude.
	 *                                                 Default empty.
	 *     @type bool|int     $multi                   Whether to skip the ID attribute on the 'select' element.
	 *                                                 Accepts 1|true or 0|false. Default 0|false.
	 *     @type string       $show                    Post table column to display. If the selected item is empty
	 *                                                 then the Post ID will be displayed in parentheses.
	 *                                                 Accepts post fields. Default 'post_title'.
	 *     @type int|bool     $echo                    Whether to echo or return the drop-down. Accepts 1|true (echo)
	 *                                                 or 0|false (return). Default 1|true.
	 *     @type int          $selected                Which post ID should be selected. Default 0.
	 *     @type string       $select_name             Name attribute of select element. Default 'post_id'.
	 *     @type string       $id                      ID attribute of the select element. Default is the value of $select_name.
	 *     @type string       $class                   Class attribute of the select element. Default empty.
	 *     @type array|string $post_status             Post status' to include, default publish
	 *     @type string       $who                     Which type of posts to query. Accepts only an empty string or
	 *                                                 'authors'. Default empty.
	 * }
	 * @return string String of HTML content.
	 *
	 * @author Quintic
	 * @website https://www.quintic.co.uk/
	 * @date    April 2019
	 *
	 * @since 1.0.0
	 */
	function q5_dropdown_posts( $args = '' ) {
/*
		$defaults = array(
			'title'					=> 'Posts',
			'empty_title'			=> 'No Posts ',
			'post_status'           => 'publish',
			'post_type'				=> 'post',
			'select_name'           => 'post_id',
			'id'                    => '',
			'ul_class'              => 'q5_dropdown',
			'li_header_class'       => 'q5_dropdown_title',
			'li_class'              => 'q5_dropdown_entry',
			'a_class'				=> 'q5_dropdown_anchor',
			'show'                  => 'post_title',
			'filter_callback'		=> '',
			'value_field'           => 'ID',
			'order'                 => 'ASC',
			'orderby'               => 'post_title',
		);
		*/
				$defaults = array(
			'selected'              => FALSE,
			'pagination'            => FALSE,
			'posts_per_page'        => - 1,
			'post_status'           => 'publish',
			'cache_results'         => TRUE,
			'cache_post_meta_cache' => TRUE,
			'echo'                  => 1,
			'select_name'           => 'post_id',
			'id'                    => '',
			'class'                 => '',
			'show'                  => '',
			'show_callback'         => NULL,
			'show_option_all'       => NULL,
			'show_option_none'      => NULL,
			'option_none_value'     => '',
			'multi'                 => FALSE,
			'value_field'           => 'ID',
			'order'                 => 'ASC',
			'orderby'               => 'post_title',
			'empty_title'			=> 'No Posts ',
			'filter_callback'		=> '',
			'ul_class'              => 'q5_dropdown',
			'li_header_class'       => 'q5_dropdown_title',
			'li_class'              => 'q5_dropdown_entry',
			'a_class'				=> 'q5_dropdown_anchor',
		);

		$r = wp_parse_args( $args, $defaults );
		
		// get required posts, filtered where necessary.
		if ($r['filter_callback'] != null)
		{
			add_action('pre_get_posts', $r['filter_callback']);
		}
		$posts  = new WP_Query( $r );
		
		if ($r['filter_callback'] != null)
		{
			remove_action('pre_get_posts', $r['filter_callback']);
		}
		
		$output = $r['empty_title'];

		if( $posts->have_posts() ) {

			$li_header = $r['li_header_class'] == null ? '<li>' : '<li class="' . $r['li_header_class'] . '"><a href="#">';
			$output = $li_header . $r['menu_title'] . " &raquo;</a>";
			$output .= $r['ul_class'] == null ? '<ul>' : '<ul class="' . $r['ul_class'] . '">';
			
			$a = $r['a_class'] == null ? '<a href="' : '<a class="' . $r['a_class'] . '" href="';
			$li =  $r['li_class'] == null ? '<li>' : '<li class="' . $r['li_class'] . '">';

			$posts = $posts->posts;
			foreach ($posts as $post)
			{ 
				$link = get_permalink($post->ID);
				if ($link == null)
				{
					$link = get_page_link($post->ID);
				}
				$output .=  $li . $a . $link . '">' . $post->post_title . '</a></li>';
			}
			$output .= '</ul></li>';
		}
		
		wp_reset_postdata();
		
		return $output;
	}
}

/*
 * Filters:
 * 1. To exclude site-pages.
 * 2. Filter only 'technical' posts/pages
 * 3. Filter only 'Photographic' posts/pages
 * 4. Filter only 'Current' posts/pages
 */
if ( ! function_exists( 'q5_filter_site_pages' )) {
	function q5_filter_site_pages( $query ){
		$cat_id = get_cat_ID('Site Page');
		$query->set('cat', '-'.$cat_id);
		return $query;
	}
}
if ( ! function_exists( 'q5_filter_technical_category' )) {
	function q5_filter_technical_category( $query ){
		$cat_id = get_cat_ID('Technical');
		$query->set('cat', $cat_id);
		return $query;
	}
}
if ( ! function_exists( 'q5_filter_photography_category' )) {
	function q5_filter_photography_category( $query ){
		$cat_id = get_cat_ID('Photography');
		$query->set('cat', $cat_id);
		return $query;
	}
}
if ( ! function_exists( 'q5_filter_current_category' )) {
	function q5_filter_current_category( $query ){
		$cat_id = get_cat_ID('Current');
		$query->set('cat', $cat_id);
		return $query;
	}
}
	
if( ! function_exists( 'q5_output_multiple' ) ) {	
	function q5_output_multiple ($text, $occurances)
	{
		for ($i = 0; $i < $occurances; $i++)
		{
			print ($text);
		}		
	}
}

if( ! function_exists( 'q5_register_post_types' ) ) {
	function q5_register_post_types() {
		register_post_type( 'products',
			array(
			'labels' => array(
						'name'=> __( 'Products' ),
						'singular_name' => __( 'Product' ),
						'add_new' => __( 'Add New Product' ),
						'add_new_item' => __('Add New Product'),
						'edit_item' => __( 'Edit Product' ),
						'new_item' => __( 'New Product' ),
						'view_item' => __( 'View Product' ),
						'not_found' => __( 'Product Not Found' )
						),
			'public' => true,
			'has_archive' => false,
			'rewrite' => array('slug' => 'product'),
			'supports' => array('title',
							'thumbnail',
							'excerpt',
							'comments'),
			'hierarchical' => true,
			'has_archive' => true
			)
		);
	}
	add_action('init', 'q5_register_post_types');
}


/* Taxonomies for post-type Product */
if( ! function_exists( 'q5_register_product_type_taxonomies' ) ) {
	function q5_register_product_type_taxonomies() {
		register_taxonomy('product_type', 
			'products', 
			array(
				'hierarchical' => true,
				'label' => 'Product Type',
				'query_var' => true,
				'rewrite' => true,
				'labels' => array (
					'name' => 'Product Types',
					'singular_name' => 'Product',
					'search_items' => 'Search Products',
					'all_items' => 'products',
					'edit_item' => 'Edit Product',
					'add_new_item' => 'Add New Product',
					'update_item' => 'Update Product',
					'new_item_name' => 'New Product Name',
					'menu_name' => 'Product Type'
				)
			)
		);
	}
	add_action('init', 'q5_register_product_type_taxonomies');
}
?>