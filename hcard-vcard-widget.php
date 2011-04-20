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
		
		$title = $instance['title'];
		$user = $instance['user'];
		$display_hcard = $instance['display_hcard'] == 'checked';
		$display_vcard = $instance['display_vcard'] == 'checked';
		
		$id = (!empty($user) ? $user : get_the_author_meta('id')); 
		$user_info = get_userdata($id);
		
		$inline = false;
		if ($args['inline'] == true)
			$inline = true;
		
		if ($user_info) {
				
			echo '<' . ($inline ? 'div id="' . $args['widget_id'] . '"' : 'li') . ' class="widget hcard-vcard-generator-widget vcard">
			<h2>' . (!empty($title) ? $title : 'Contact <span class="fn">' . $user_info->display_name . '</span>') . '</h2>
			<br />
			<div>' .
			($display_hcard ? generate_card($id, 'hCard') : '') . 
			($display_vcard ? generate_card($id, 'vCard') : '') .
			'</div>
			</' . ($inline ? 'div' : 'li') . '>';
		}
		
	}

	function update($new_instance, $old_instance) {
		//print_r($_POST);
		
		$instance = $old_instance;
		$new_instance = (array)$new_instance;

		$instance['title'] = (!empty($new_instance['title']) ? strip_tags($new_instance['title']) : '');
		$instance['user'] = (is_numeric(strip_tags($new_instance['user'])) ? strip_tags($new_instance['user']) : '');
		$instance['display_hcard'] = ($new_instance['display_hcard'] == 'on' ? 'checked' : '');
		$instance['display_vcard'] = ($new_instance['display_vcard'] == 'on' ? 'checked' : '');
		
		/* echo "NEW INFO: \n\n";
		print_r($instance);
		exit(); */ 
		return $instance;
	}

	function form($instance) {

		//Defaults
		$defaults = array(
			'title' => '',
			'user' => '',
			'display_hcard' => 'checked',
			'display_vcard' => 'checked'
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
			<br /><span style="color:#808080;font-size:8pt;">Leave blank for auto title generation</span>
			</p>
			
			<p>
			<label for="' . 
			$this->get_field_id('user') . 
			'">User ID:</label>
			<input type="text" id="' . 
			$this->get_field_id('user') . 
			'" name="' . 
			$this->get_field_name('user') . 
			'" value="' . 
			$instance['user'] . '" />
			</p>
			
			<p>
			<label for="' . 
			$this->get_field_id('display_hcard') . 
			'">Display hCard?:</label>
			<input type="checkbox" id="' . 
			$this->get_field_id('display_hcard') . 
			'" name="' . 
			$this->get_field_name('display_hcard') . '"' . 
			(!empty($instance['display_hcard']) ? ' checked="' . $instance['display_hcard'] . '"' : '') . ' />
			</p>
			
			<p>
			<label for="' . 
			$this->get_field_id('display_vcard') . 
			'">Display vCard?:</label>
			<input type="checkbox" id="' . 
			$this->get_field_id('display_vcard') . 
			'" name="' . 
			$this->get_field_name('display_vcard') . '"' .  
			(!empty($instance['display_vcard']) ? ' checked="' . $instance['display_vcard'] . '"' : '') . ' />
			</p>';
		
	}
}

?>
