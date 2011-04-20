<?php

/*******************************************************************************
** Allows user to specify a shortcode in the page/post to include the case 
** studies widget. 
**
** Expected format:
**
** [casestudies title="Projects: " numposts="5" areas="developers,multi-res"]
**
*******************************************************************************/
function cardByShortcode($atts) {
    global $wp_widget_factory;
    
    extract(
    	shortcode_atts(
    		array(
    			'title' => '',
    			'user' => '',
    			'display_hcard' => '',
    			'display_vcard' => ''
    		), 
    		$atts
    	)
    );
    
    $widget_name = 'Widget_hCard_vCard';
    
	$instance = "title=$title";
    
    if (!empty($user)) $instance .= "&user=$user";
    if (!empty($display_hcard)) $instance .= "&display_hcard=checked";
    if (!empty($display_vcard)) $instance .= "&display_vcard=checked";
    if (!empty($keyword)) $instance .= "&keyword=$keyword";
        
    if (!is_a($wp_widget_factory->widgets[$widget_name], 'WP_Widget')) {
        $wp_class = 'WP_Widget_' . ucwords(strtolower($class));
        
        if (!is_a($wp_widget_factory->widgets[$wp_class], 'WP_Widget')) {
            return '<p>ERROR: Widget_hCard_vCard class has not been initialized properly.</p>';
    	} else {
            $class = $wp_class;
    	}
    }
    
    ob_start();
    
    the_widget(
    	$widget_name, 
    	$instance, 
    	array(
			'widget_id' => 'shortcode-hcard-vcard-widget-' . $id,
			'before_widget' => '',
			'after_widget' => '',
			'before_title' => '',
			'after_title' => '',
			'inline' => true // tells widget not to wrap itself in list tags
		)
	);
	
    $output = ob_get_contents();
    
    ob_end_clean();
    
    return $output;
    
}

add_shortcode('hcardvcard','cardByShortcode',1); 

?>
