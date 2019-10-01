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
