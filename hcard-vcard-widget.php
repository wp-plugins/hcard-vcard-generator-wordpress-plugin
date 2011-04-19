<?php
/**
 * hCard vCard Widget Class
 *                 
 * @since 3.0
 */
class Widget_hCard_vCard extends WP_Widget {

	function Widget_hCard_vCard() {

		$widget_ops = array( 
			'description' => 'Gets the current author\'s hCard'
		);
		
		$this->WP_Widget( 
			'hcard-vcard-generator', 
			'hCard & vCard Generator Widget', 
			$widget_ops
		);
		
	}

	function widget($args, $instance) {
		extract( $args, EXTR_SKIP );

		$args = array();

		$title = $instance['title'];
		
		$id = get_the_author_meta('id'); 
		$user_info = get_userdata($id);
		
		if ($user_info) {
				
			echo '<li class="widget hcard-vcard-generator-widget vcard">
			<h2>Contact <span class="fn">' . $user_info->first_name . ' ' . 
			$user_info->last_name . '</span></h2>
			<br />
			<div>' .
			generate_card($id, 'hCard') . 
			generate_card($id, 'vCard') .
			'</div>
			</li>';
		}
			
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$new_instance = (array)$new_instance;

		$instance['title'] = strip_tags( $new_instance['title'] );
		
		return $instance;
	}

	function form($instance) {

		//Defaults
		$defaults = array(
			'title' => 'Contact Information',
		);
		
		$instance = wp_parse_args( (array)$instance, $defaults );
		echo '<p>
			<label for="' . 
			$this->get_field_id('title') . 
			'">Title:</label>
			<input type="text" id="' . 
			$this->get_field_id('title') . 
			'" name="' . 
			$this->get_field_name('title') . 
			'" value="' . 
			$instance['title'] . '" />
			</p>';
		
	}
}

?>
