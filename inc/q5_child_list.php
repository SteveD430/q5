<?php
class q5_child_entry
{
	private $heading;
	private $anchor;

	public function __construct ($heading, $anchor)
	{
		$this->heading = $heading;
		$this->anchor = $anchor;
//		$this->level  = $level;
	}

	public function get_heading()
	{
		return $this->heading;
	}

	public function get_anchor()
	{
		return $this->anchor;
	}
}

class q5_toc
{
	private $child_entries;

	public function __construct ()
	{
		$this->child_entries = array();
	}

	public function get_child_entry()
	{
		foreach($this->child_entries as $child_entry)
		{
			yield $child_entry;
		}
	}
	
	public function get_child_entry_count()
	{
		return count($this->child_entries);
	}

	public function build_child_list()
	{
		global $post;
		
		if ( is_page() && $post->post_parent)
		{
			$childpages = wp_list_pages('sort_column=menu_order&title_li=&child_of=' . $post->post_parent . $echo=0');
		}
		else
		{
			$childpages = wp_list_pages('sort_column=menu_order&title_li=&child_of=' . $post->ID . $echo=0');
		}
		if ( $childpages)
		{
			$string ='<ul>' . $childpages . '</ul>';
		}
		return string;
	}
	
	public function render_child_list ( $args = '' )
	{
		$defaults = array(
			'child_class'             => 'q5_child_item',
			'child_title_class'       => 'q5_child_list_title',
			'child_title'             => 'Topics:',
		);

		$r = wp_parse_args( $args, $defaults );
		
//		$current_level = 0;
		$child_entry_element = $r['child_entry_class'] == '' ? '<a' : '<a class="' . $r['child_entry_class'] . '"';
//		$toc_heirarchy_element = $r['toc_heirarchy_class'] == '' ? '<ul>' : '<ul class="' . $r['toc_heirarchy_class'] . '">';

		echo '<div class="' . $r['child_class'] . '">';
		echo '<p class="' . $r['child_title_class'] .'">';
		echo __($r['child_title']);
		echo '</p>';
		foreach ($this->get_child_entry() as $child_entry)
		{
/*
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
*/
			echo $child_entry_element . ' href="#' . $child_entry->get_anchor() . '">' . $child_entry->get_heading() . '</a>';
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
	
	static public function construct_anchor ($id)
	{
		return Q5_ANCHOR_PREFIX . $id;
	}
}

class q5_child_list_widget extends WP_Widget
{
	public function __construct()
	{
		parent::__construct ('q5_child_list_widget', 'Q5 Child List Widget');
	}
	
	public function widget ($args, $instance)
	{
		q5_debug('From within my widget');
		$post = get_post( $wp_query->post->ID );
		
		$post->post_content = q5_add_anchor_points_content_filter(wptexturize($post->post_content));
		
		if ($GLOBALS['q5_child_list'] == null)
		{
			q5_debug("GLOBALS[q5_child_list] null");
			return;
		}
		$GLOBALS['q5_child_list']->render_child_list();
	}
}

?>
