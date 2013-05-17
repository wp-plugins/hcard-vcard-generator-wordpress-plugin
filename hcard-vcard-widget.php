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
		$autogen_title = $instance['autogen_title'] == 'checked';
		
		$id = (!empty($user) ? $user : get_the_author_meta('id'));
		
		if (is_numeric($id)) {
			$user_info = get_userdata($id);
		} else {
			$user_info = get_userdatabylogin($user);
			$id = $user_info->ID;
		}
		
		$inline = false;
		if ($args['inline'] == true)
			$inline = true;
		
		if ($user_info) {
				
			echo '<' . ($inline ? 'div id="' . $args['widget_id'] . '"' : 'li') . ' class="widget hcard-vcard-generator-widget vcard">';
			
			if (!$autogen_title) {
				echo (!empty($title) ? '<h2 class="hcard-vcard-title">' . $title . '</h2>' : '');
			} else {
				echo '<h2 class="mp-vcard-title">Contact <span class="fn">' . $user_info->first_name . ' ' . $user_info->last_name . '</span></h2>';
			}
			
			echo '<div>' .
			($display_hcard ? generate_card($id, 'hCard') : '') . 
			($display_vcard ? generate_card($id, 'vCard') : '') .
			'</div>
			</' . ($inline ? 'div' : 'li') . '>';
		}
		
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$new_instance = (array)$new_instance;

		$instance['title'] = (!empty($new_instance['title']) ? strip_tags($new_instance['title']) : '');
		$instance['autogen_title'] = ($new_instance['autogen_title'] == 'on' ? 'checked' : '');
		$instance['user'] = !empty($new_instance['user']) ? $new_instance['user'] : '';
		$instance['display_hcard'] = ($new_instance['display_hcard'] == 'on' ? 'checked' : '');
		$instance['display_vcard'] = ($new_instance['display_vcard'] == 'on' ? 'checked' : '');
		
		return $instance;
	}

	function form($instance) {

		//Defaults
		$defaults = array(
			'title' => '',
			'autogen_title' => 'checked',
			'user' => '',
			'display_hcard' => 'checked',
			'display_vcard' => 'checked'
		);
		
		//echo '<pre>' . print_r($instance, true) . '</pre>';
		
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
			</p>
			
			<p>
			<label for="' . 
			$this->get_field_id('autogen_title') . 
			'">Auto Generate Title?:</label>
			<input type="checkbox" id="' . 
			$this->get_field_id('autogen_title') . 
			'" name="' . 
			$this->get_field_name('autogen_title') . '"' . 
			(!empty($instance['autogen_title']) ? ' checked="' . $instance['autogen_title'] . '"' : '') . ' />
			<br /><span style="font-size: 8px; color: #808080; font-style: italic;">Overrides any title that you\'ve set</span>
			</p>
			
			<p>
			<label for="' . 
			$this->get_field_id('user') . 
			'">User:</label>
			<input type="text" id="' . 
			$this->get_field_id('user') . 
			'" name="' . 
			$this->get_field_name('user') . 
			'" value="' . 
			$instance['user'] . '" /><br /><span style="font-size: 8px; color: #808080; font-style: italic;">User\'s ID or login</span>
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
