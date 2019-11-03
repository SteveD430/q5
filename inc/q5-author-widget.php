<?php
class Q5_Author extends WP_Widget {

	function __construct() {
		
		parent::__construct('q5-author', 'Q5 Author');
		
		// Register widget
		add_action ('widgets_init', function() {
					register_widget('Q5_Author');
		});
	}
	
	public $args = array(
		'before_title'	=> '<p><span class="q5_author_title">',
		'after_title' 	=> '</span>',
		'before_widget'	=> '<div>',
		'after_widget' 	=> '</div>'
	);
	
	public function widget ( $args, $instance)
	{
		
		q5_debug('Before Widget: '. $this->args['before_widget']);
		q5_debug('After Widget: '. $this->args['after_widget']);
		q5_debug('Before Title: '. $this->args['before_title']);
		q5_debug('After Title: '. $this->args['after_title']);
		
		echo $this->args['before_widget'] . $this->args['before_title'];
		echo _e('Author') . ': '. $this->args['after_title'];
		echo '<span class="q5_author">' . get_the_author_meta('display_name') . '</span></p>' . $this->args['after_widget'];
	}
	
	public function form ($instance){
	}
	
	public function update ($new_instance, $old_instance){
	}
}
$q5_author_widget = new Q5_Author();

?>
