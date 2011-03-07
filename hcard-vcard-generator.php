<?php
/*
* Plugin Name: hCard & vCard Generator
*
* Description: Given a user ID, this plugin will generate appropriate hCard and 
* vCard formats for inserting into pages wherever you like.
*
* Author: Josh Kohlbach
* Author URI: http://codemyownroad.com
* Plugin URI: http://www.codemyownroad.com/products/hcard-vcard-generator-wordpress-plugin/
* Version: 1.0
*/

/*
Examples of format outputs:
---------------------------

vCard Format:

BEGIN:VCARD
VERSION:3.0
N:Gump;Forrest
FN:Forrest Gump
ORG:Bubba Gump Shrimp Co.
TITLE:Shrimp Man
TEL;TYPE=WORK,VOICE:(111) 555-1212
EMAIL;TYPE=PREF,INTERNET:forrestgump@example.com
END:VCARD

hCard Format:
<div class="vcard">
	<span class="fn">Forrest Gump</span>
	<a class="url" href="http://example.com">http://example.com</a>
	<span class="tel">
		<span class="type">Work</span>:
		<span class="value">+1.415.555.1212</span>
	</span>
</div>
*/

/*******************************************************************************
** generate_card()
**
** Generate the [h|v]card.
**
** @since 1.0
*******************************************************************************/
function generate_card($user_id, $type = 'hCard') {
	$user_info = get_userdata($user_id);
	
	// Time to build up the selected Card Format
	if ($type == 'hCard'|| $type == 'vCard') {
		
		if ($type == 'hCard') {
			
			// Generate the hCard
			if (!empty($user_info->user_phone_work))
				$html .= '<div class="tel">
			<span class="type work">Tel: </span> ' . 
				$user_info->user_phone_work . '</p>';
				
			if (!empty($user_info->user_phone_mobile))
				$html .= '<div class="tel">
				<span class="type cell">Cell</span> ' . 
				$user_info->user_phone_mobile . '</div>';
			
			if (!empty($user_info->user_organization))
				$html .= '<div class="org url">
				<a href="' . $user_info->user_url . '">' .
				$user_info->user_organization . '</a></div>';
			
			if (!empty($user_info->user_job_title))
				$html .= '<div class="title" style="display:none;">' . 
				$user_info->user_job_title . '</div>';
			
			if (!empty($user_info->user_email))
				$html .= '<div class="email" style="display:none;">' . 
				'<a href="' . $user_info->user_email . '">' . 
				$user_info->user_email . '</a></div>';
				
		} else {
			
			// Generate the vCard and save as a file
			$fileContents = 'BEGIN:VCARD
VERSION:3.0
N:' . $user_info->last_name . ';' . $user_info->first_name . '
FN:' . $user_info->display_name . '
ORG:' . $user_info->user_organization . '
URL:' . $user_info->user_url . '
TITLE:' . $user_info->user_job_title . '
TEL;TYPE=WORK,VOICE:' . $user_info->user_phone_work . '
TEL;TYPE=CELL,VOICE:' . $user_info->user_phone_mobile . '
EMAIL;TYPE=PREF,INTERNET:' . $user_info->user_email . '
END:VCARD';
			
			$userVCF = ABSPATH . '/wp-content/plugins/hcard-vcard-generator/' . 
				$user_info->user_login . '.vcf';
			
			//if (!file_exists($userVCF)) {
				$vcfFile = fopen($userVCF, "w");
				fwrite($vcfFile, $fileContents);
				fclose($vcfFile);
			//}
		
			$html .= '
				<div class="vcard_button">
					<a href="' . get_bloginfo('url') . 
					'/wp-content/plugins/hcard-vcard-generator/' . 
					$user_info->user_login . '.vcf">Download vCard</a>
				</div>';
		}
	} else {
		// Not a valid type, return nothing.
		return null;
	}
	
	return $html;
}

/*******************************************************************************
** add_additional_user_fields()
**
** Add extra user fields to the user edit page.
**
** @since 1.0
*******************************************************************************/
function add_additional_user_fields() {
	global $user_id;
	
	$user_organization = get_user_meta($user_id, 'user_organization', true);
	$user_job_title = get_user_meta($user_id, 'user_job_title', true);
	$user_phone_work = get_user_meta($user_id, 'user_phone_work', true);	
	$user_phone_mobile = get_user_meta($user_id, 'user_phone_mobile', true);
	
	echo '<h3>Additional Information (vCard & hCard)</h3>
	<table class="form-table">
	<tr>
		<th>
			<label for="user_organization">Organization: </label>
		</th>
		<td>
			<input name="user_organization" id="user_organization" value="' . 
			$user_organization . '" class="regular_text" type="text" />
		</td>
	</tr>
	
	<tr>
		<th>
			<label for="user_job_title">Job Title: </label>
		</th>
		<td>
			<input name="user_job_title" id="user_job_title" value="' . 
			$user_job_title . '" class="regular_text" type="text" />
		</td>
	</tr>
	
	<tr>
		<th>
			<label for="user_phone_work">Phone (Work): </label>
		</th>
		<td>
			<input name="user_phone_work" id="user_phone_work" value="' . 
			$user_phone_work . '" class="regular_text" type="text" />
		</td>
	</tr>

	<tr>
		<th>
			<label for="user_phone_mobile">Phone (Mobile): </label>
		</th>
		<td>
			<input name="user_phone_mobile" id="user_phone_mobile" value="' . 
			$user_phone_mobile . '" class="regular_text" type="text" />
		</td>
	</tr>	
	
	</table>';
}

/*******************************************************************************
** save_additional_user_fields()
**
** Save the additional user fields on the user edit page
**
** @since 1.0
*******************************************************************************/
function save_additional_user_fields() {
	global $user_id;
	
	if ( !current_user_can( 'edit_user', $user_id ) ) 
		return false; 

	/* Save Organization */
	$user_organization = $_POST['user_organization'];
	if (!empty($user_organization)) update_usermeta($user_id, 'user_organization', $user_organization);
	else delete_user_meta($user_id, 'user_organization');
	
	/* Save Job Title */
	$user_job_title = $_POST['user_job_title'];
	if (!empty($user_job_title)) update_usermeta($user_id, 'user_job_title', $user_job_title);
	else delete_user_meta($user_id, 'user_job_title');
	
	/* Save Phone (Work) */
	$user_phone_work = $_POST['user_phone_work'];
	if (!empty($user_phone_work)) update_usermeta($user_id, 'user_phone_work', $user_phone_work);
	else delete_user_meta($user_id, 'user_phone_work');
	
	/* Save Phone (Mobile) */
	$user_phone_mobile = $_POST['user_phone_mobile'];
	if (!empty($user_phone_mobile)) update_usermeta($user_id, 'user_phone_mobile', $user_phone_mobile);
	else delete_user_meta($user_id, 'user_phone_mobile');
	
}

/*******************************************************************************
** init_hcard_vcard_generator()
**
** Initialize the plugin
**
** @since 1.0
*******************************************************************************/
function init_hcard_vcard_generator() {
	require_once('hcard-vcard-widget.php');
	register_widget('Widget_hCard_vCard');
	add_action('show_user_profile', 'add_additional_user_fields');
	add_action('edit_user_profile', 'add_additional_user_fields');
	add_action('edit_user_profile_update', 'save_additional_user_fields');
	add_action('personal_options_update', 'save_additional_user_fields');
}

add_action('init', 'init_hcard_vcard_generator', 1);

?>
