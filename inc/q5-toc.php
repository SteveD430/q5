<?php
define ('Q5_ANCHOR_PREFIX', 'q5_anchor');

class q5_toc_entry
{
	private $heading;
	private $anchor;
	private $level;

	public function __construct ($heading, $anchor, $level)
	{
		$this->heading = $heading;
		$this->anchor = $anchor;
		$this->level  = $level;
	}

	public function get_heading()
	{
		return $this->heading;
	}

	public function get_anchor()
	{
		return $this->anchor;
	}

	public function get_level()
	{
		return $this->level;
	}
}

class q5_toc
{

	private $anchor_id;
	private $toc_entries;

	public function __construct ()
	{
		$this->anchor_id = 0;
		$this->toc_entries = array();
	}

	public function get_toc_entry()
	{
		foreach($this->toc_entries as $toc_entry)
		{
			yield $toc_entry;
		}
	}
	
	public function get_toc_entry_count()
	{
		return count($this->toc_entries);
	}
	
	public function build_toc ( $content )
	{
		$dom = new DOMDocument();
		$dom->loadHTML( $content );
		foreach ($dom->childNodes as $child)
		{
			$this->find_toc_elements ($this, $dom, $child);
		}
	}
	
	public function render_toc ( $args = '' )
	{
		$defaults = array(
			'toc_class'             => 'q5_toc',
			'toc_title_class'       => 'q5_toc_title',
			'toc_title'             => 'Table of Contents',
			'toc_entry_class'       => '',
			'toc_heirarchy_class'   => '',
		);

		$r = wp_parse_args( $args, $defaults );
		
		$current_level = 0;
		$toc_entry_element = $r['toc_entry_class'] == '' ? '<li>' : '<li class="' . $r['toc_entry_class'] . '">';
		$toc_heirarchy_element = $r['toc_heirarchy_class'] == '' ? '<ul>' : '<ul class="' . $r['toc_heirarchy_class'] . '">';

		echo '<div class="' . $r['toc_class'] . '">';
		echo '<p class="' . $r['toc_title_class'] .'">';
		echo __($r['toc_title']);
		echo '</p>';
		foreach ($this->get_toc_entry() as $toc_entry)
		{
			$indent = $toc_entry->get_level() - $current_level;
			if ($indent > 0)
			{
				$this->step_in($toc_heirarchy_element, $indent);
			}
			else if ($indent < 0)
			{
				$this->step_out(-$indent);
			}
			$current_level = $toc_entry->get_level();

			echo $toc_entry_element . '<a href="#' . $toc_entry->get_anchor() . '">' . $toc_entry->get_heading() . '</a></li>';
		}
		$this->step_out($current_level);
		echo "</div>";
	}
	
	private function step_in ($ulelement, $indent)
	{
		q5_output_multiple ($ulelement, $indent);
	}
	
	private function step_out ($indent)
	{
		q5_output_multiple ('</ul>', $indent);
	}
	
	private function find_toc_elements ($toc, $dom, $node)
	{
		if (q5_toc_definition::is_toc_element($node))
		{
			$heading = $node->firstChild->nodeValue;
			$anchor = $node->getAttribute('id');


			if ($anchor == null)
			{
				$anchor = q5_toc_definition::construct_anchor ($this->anchor_id++);
			}
			$level = q5_toc_definition::toc_level ($node);

			$this->add_toc_entry(new q5_toc_entry($heading, $anchor, $level));
		}

		if ($node->childNodes != null)
		{
			foreach($node->childNodes as $childNode)
			{
				$this->find_toc_elements ($toc, $dom, $childNode);
			}
		}
	}

	private function add_toc_entry(q5_toc_entry $toc_entry)
	{
		$this->toc_entries[] = $toc_entry;
	}
}

class q5_toc_definition
{	
	static private $toc_elements = array(
		'h1' => 1, 
		'h2' => 2, 
		'h3' => 3, 
		'h4' => 4,
		'h5' => 5,
		'h6' => 6);
		
		
	static public function is_toc_element($element)
	{
		return array_key_exists($element->nodeName, q5_toc_definition::$toc_elements);
	}
	
	static public function toc_elements()
	{
		return $toc_elements::array_keys();
	}
	
	static public function toc_level ($element)
	{
		return q5_toc_definition::is_toc_element($element) ? 
			q5_toc_definition::$toc_elements[$element->nodeName] :
			-1;
	}
	
	static public function construct_anchor ($id)
	{
		return Q5_ANCHOR_PREFIX . $id;
	}
}

/**
 * function q5_list_child_pages
 * ======================
 * Return a list of child pages as HTML.
 *
 * @since 1.0.0
 *
 * @see get_pages()
 *
 * @global WP_Query $wp_query
 *
 * @param post $post 	Current Page.
 * @param array|string $args {
 *     Optional. Array or string of arguments to generate a list of pages. See `get_pages()` for additional arguments.
 *
 *     @type string       $date_format  PHP date format to use for the listed pages. Relies on the 'show_date' parameter.
 *                                      Default is the value of 'date_format' option.
 *     @type int          $depth        Number of levels in the hierarchy of pages to include in the generated list.
 *                                      Accepts -1 (any depth), 0 (all pages), 1 (top-level pages only), and n (pages to
 *                                      the given n depth). Default 1.
 *     @type string		  $title_class	CSS Class of the title_class
 *	   @type string		  $entry_class  CSS Class of each entry
 *	   @type string		  $date_class   CSS class of Date field, if requested.
 *     @type string       $exclude      Comma-separated list of page IDs to exclude. Default empty.
 *     @type array        $include      Comma-separated list of page IDs to include. Default empty.
 *     @type string       $link_after   Text or HTML to follow the page link label. Default null.
 *     @type string       $link_before  Text or HTML to precede the page link label. Default null.
 *     @type string       $post_type    Post type to query for. Default 'page'.
 *     @type string|array $post_status  Comma-separated list or array of post statuses to include. Default 'publish'.
 *     @type string       $show_date    Whether to display the page publish or modified date for each page. Accepts
 *                                      'modified' or any other value. An empty value hides the date. Default empty.
 *     @type string       $sort_column  Comma-separated list of column names to sort the pages by. Accepts 'post_author',
 *                                      'post_date', 'post_title', 'post_name', 'post_modified', 'post_modified_gmt',
 *                                      'menu_order', 'post_parent', 'ID', 'rand', or 'comment_count'. Default 'post_title'.
 *     @type string       $title_child  List heading. Default 'Pages'.
 *     @type string       $item_spacing Whether to preserve whitespace within the menu's HTML. Accepts 'preserve' or 'discard'.
 *                                      Default 'preserve'.
 *     @type Walker       $walker       Walker instance to use for listing pages. Default empty (Walker_Page).
 *
 *	   @return string - HTML to insert into widget/page.
 * }
 * 
 */
function q5_list_child_pages( $post, $args = '' ) {
	$defaults = array(
		'depth'        => 0,
		'show_date'    => '',
		'date_format'  => get_option( 'date_format' ),
		'exclude'      => '',
		'title_child'  => __( 'Pages' ),
		'sort_column'  => 'menu_order, post_title',
		'link_before'  => '',
		'link_after'   => '',
		'item_spacing' => 'preserve',
		'walker'       => '',
		'section_class'=> '',
		'title_class'  => '',
		'entry_class'  => '',
		'date_class'   => '',
		'post_status'  => 'publish',
	);
	$r = wp_parse_args( $args, $defaults );

	if ( ! in_array( $r['item_spacing'], array( 'preserve', 'discard' ), true ) ) 
	{
		// invalid value, fall back to default.
		$r['item_spacing'] = $defaults['item_spacing'];
	}

	// sanitize, mostly to keep spaces out
	$r['exclude'] = preg_replace( '/[^0-9,]/', '', $r['exclude'] );

	// Allow plugins to filter an array of excluded pages (but don't put a nullstring into the array)
	$exclude_array = ( $r['exclude'] ) ? explode( ',', $r['exclude'] ) : array();

	/**
	 * Filters the array of pages to exclude from the pages list.
	 *
	 * @since 2.1.0
	 *
	 * @param array $exclude_array An array of page IDs to exclude.
	 */
	$r['exclude'] = implode( ',', apply_filters( 'wp_list_pages_excludes', $exclude_array ) );

	// Query pages.
	$r['hierarchical'] = 0;
	$pages             = get_pages( $r );
	$section_start_function = 'q5_add_section_start';
	$section_end_function = 'q5_null_function';

	if ( ! empty( $pages ) ) 
	{


		foreach ( (array) $pages as $child_page ) 
		{

			if ($child_page->post_parent !=  0 && $child_page->post_parent == $post->ID)
			{
				$output .= $section_start_function($r);
				$section_start_function = 'q5_null_function';
				$section_end_function = 'q5_add_section_end';
				$link = get_permalink($child_page->ID);
				if ($link == null)
				{
					$link = get_page_link($child_page->ID);
				}
				$output .= '<li><a  class="'. $r['entry_class'] . '" href="' . $link .'">' . $child_page->post_title . '</a></li>';
			}
		}
		$output .= $section_end_function();
		return $output;
	}
}

function q5_add_section_start($args)
{
	$output = '<div class="' . $args['section_class'] . '">';
	if ( $args['title_child'] ) 
	{
		$output .= '<p class="' . $args['title_class'] . '">' . $args['title_child'] . '</p><ul>';
	}
	return $output;
}

function q5_add_section_end()
{
	return '</ul></div>';
}

function q5_null_function ($args = '')
{
}


/**
 * function q5_list_parent
 * =======================
 * Return a link to parent page as HTML.
 *
 * @since 1.0.0
 *
 * @param post $post 	Current Page.
 * @param array|string $args {
 *     Optional. Array or string of arguments to generate a list of pages. See `get_pages()` for additional arguments.
 *
 *     @type string		  $section_class CSS Class of the section (<div>
 *     @type string		  $title_class	 CSS Class of the title 
 *	   @type string		  $entry_class   CSS Class of each entry
 
 *     @type string       $title_parent  List heading. Default 'Parent'.
 *     @type string       $item_spacing Whether to preserve whitespace within the menu's HTML. Accepts 'preserve' or 'discard'.
 *                                      Default 'preserve'.
 *
 *	   @return string - HTML to insert into widget/page.
 * 
 */
function q5_list_parent( $post, $args = '' ) {
	$defaults = array(
		'title_parent'  => __( 'Parent' ),
		'section_class'=> '',
		'title_class'  => '',
		'entry_class'  => '',
		'item_spacing' => 'preserve',
	);
	$output = '';
	
	$r = wp_parse_args( $args, $defaults );

	if ( ! in_array( $r['item_spacing'], array( 'preserve', 'discard' ), true ) ) 
	{
		// invalid value, fall back to default.
		$r['item_spacing'] = $defaults['item_spacing'];
	}

	
	q5_debug ('Parent Id: ' . $post->post_parent);
	if ( $post->post_parent != 0)
	{

		$parent_page = get_post($post->post_parent);
		$link = get_permalink($post->post_parent);
		if ($link == null)
		{
			$link = get_page_link($post->post_parent);
		}
		$output = '<div class="' . $r['section_class'] . '">';
		$output .= '<p class="' . $r['title_class'] . '">' . $r['title_parent'] . '</p>';
		$output .= '<a class="'. $r['entry_class'] . '" href="' . $link .'">' . $parent_page->post_title . '</a>';
		$output .= '</div>';
	}
	
	return $output;
}

class q5_toc_widget extends WP_Widget
{
	public function __construct()
	{
		parent::__construct ('q5_toc_widget', 'Q5 TOC Widget');
	}
	
	public function widget ($args, $instance)
	{
		q5_debug('From within my widget');
		$post = get_post( $wp_query->post->ID );
		
		$post->post_content = q5_add_anchor_points_content_filter(wptexturize($post->post_content));
		
		if ($GLOBALS['q5_toc'] == null)
		{
			q5_debug("GLOBALS[q5_toc] null");
			return;
		}
		$GLOBALS['q5_toc']->render_toc();
	}
}

?>
