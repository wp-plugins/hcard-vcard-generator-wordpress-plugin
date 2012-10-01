<?php
/*
* Plugin Name: hCard & vCard Generator
*
* Description: Generates hCard and vCard microformats for inserting into pages wherever you like.
*
* Author: Josh Kohlbach
* Author URI: http://codemyownroad.com
* Plugin URI: http://www.codemyownroad.com/products/hcard-vcard-generator-wordpress-plugin/
* Version: 1.9
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
ADR
END:VCARD

hCard Format:
<div class="vcard">
	<span class="fn">Forrest Gump</span>
	<a class="url" href="http://example.com">http://example.com</a>
	<div class="tel">
		<span class="type">Work</span>:
		<span class="value">+1.415.555.1212</span>
	</div>
	<div class="adr">
		<div class="street-address">665 3rd St.</div>
		<div class="extended-address">Suite 207</div>
		<span class="locality">San Francisco</span>,
		<span class="region">CA</span>
		<span class="postal-code">94107</span>
		<div class="country-name">U.S.A.</div>
	</div>
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
	$html = '';
	// Time to build up the selected Card Format
	if ($type == 'hCard'|| $type == 'vCard') {
		
		if ($type == 'hCard') {
			
			// Generate the hCard
			if (!empty($user_info->user_photourl))
				$html .= '<img src="' . $user_info->user_photourl . 
				'" class="photo" alt="' . 
				$user_info->first_name . ' ' . $user_info->last_name . '" />';
			
			if (!empty($user_info->user_phone_work))
				$html .= '<div class="tel">
			<span class="type work">Tel: </span> ' . 
				$user_info->user_phone_work . '</div>';
				
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
				
			if (!empty($user_info->user_note))
				$html .= '<div class="note" style="display:none;">' . 
				$user_info->user_note . '</div>';
			
			if (!empty($user_info->user_email))
				$html .= '<div class="email" style="display:none;">' . 
				'<a href="mailto:' . $user_info->user_email . '">' . 
				$user_info->user_email . '</a></div>';
				
			if (!empty($user_info->user_street_address_line_1))
				$html .= '<div class="adr">
					<div class="street-address">' . $user_info->user_street_address_line_1 . '</div>
					<div class="extended-address">' . $user_info->user_street_address_line_2 . '</div>
					<span class="locality">' . $user_info->user_locality . '</span>,
					<span class="region">' . $user_info->user_region . '</span>
					<span class="postal-code">' . $user_info->user_postcode . '</span>
					<div class="country-name">' . $user_info->user_country . '</div>
				</div>';
			
		} else {
			
			// Generate the vCard and save as a file
			$fileContents = 'BEGIN:VCARD
VERSION:3.0
N:' . (!empty($user_info->last_name) ? $user_info->last_name : '') . ';' . (!empty($user_info->first_name) ? $user_info->first_name : '') . '
FN:' . (!empty($user_info->display_name) ? $user_info->display_name : '') . '
URL:' . (!empty($user_info->user_url) ? $user_info->user_url : '') . '
PHOTO:' . (!empty($user_info->user_photourl) ? $user_info->user_photourl : '') . '
ORG:' . (!empty($user_info->user_organization) ? $user_info->user_organization : '') . '
NOTE:' . (!empty($user_info->user_note) ? $user_info->user_note : '') . '
TITLE:' . (!empty($user_info->user_job_title) ? $user_info->user_job_title : '') . '
TEL;TYPE=WORK,VOICE:' . (!empty($user_info->user_phone_work) ? $user_info->user_phone_work : '') . '
TEL;TYPE=CELL,VOICE:' . (!empty($user_info->user_phone_mobile) ? $user_info->user_phone_mobile : '') . '
EMAIL;TYPE=PREF,INTERNET:' . (!empty($user_info->user_email) ? $user_info->user_email : '') . '
ADR:' . (!empty($user_info->user_street_address_line_1) ? $user_info->user_street_address_line_1 : '') . ';' . 
(!empty($user_info->user_street_address_line_2) ? $user_info->user_street_address_line_2 : '') . ';' .
(!empty($user_info->user_locality) ? $user_info->user_locality : '') . ';' .
(!empty($user_info->user_region) ? $user_info->user_region : '') . ';' .
(!empty($user_info->user_postcode) ? $user_info->user_postcode : '') . ';' .
(!empty($user_info->user_country) ? $user_info->user_country : '') . '' . '
END:VCARD';
			
			$userVCF = trailingslashit(ABSPATH) . 'wp-content/plugins/hcard-vcard-generator-wordpress-plugin/' . 
				$user_info->user_login . '.vcf';
						
			if (is_writable(trailingslashit(ABSPATH) . 'wp-content/plugins/hcard-vcard-generator-wordpress-plugin')) {
				$vcfFile = fopen($userVCF, "w");
				fwrite($vcfFile, $fileContents);
				fclose($vcfFile);
				$html .= '
				<div class="vcard_button">
					<a href="' . get_bloginfo('wpurl') . 
					'/wp-content/plugins/hcard-vcard-generator-wordpress-plugin/' . 
					$user_info->user_login . '.vcf">Download vCard</a>
				</div>';
			} else {
				echo "ERROR: Please ensure the hCard/vCard generator plugin directory is writable";
			}
			
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
	$user_photourl = get_user_meta($user_id, 'user_photourl', true);
	$user_note = get_user_meta($user_id, 'user_note', true);
	$user_job_title = get_user_meta($user_id, 'user_job_title', true);
	$user_phone_work = get_user_meta($user_id, 'user_phone_work', true);	
	$user_phone_mobile = get_user_meta($user_id, 'user_phone_mobile', true);
	$user_street_address_line_1 = get_user_meta($user_id, 'user_street_address_line_1', true);
	$user_street_address_line_2 = get_user_meta($user_id, 'user_street_address_line_2', true);
	$user_locality = get_user_meta($user_id, 'user_locality', true);
	$user_region = get_user_meta($user_id, 'user_region', true);
	$user_postcode = get_user_meta($user_id, 'user_postcode', true);
	$user_country = get_user_meta($user_id, 'user_country', true);
	
	echo '<h3>Additional Information (vCard & hCard)</h3>
	<table class="form-table">
	
	<tr>
		<th>
			<label for="user_photourl">Photo (URL): </label>
		</th>
		<td>
			<input name="user_photourl" id="user_photourl" value="' . 
			$user_photourl . '" class="regular_text" type="text" />
		</td>
	</tr>
	
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
	
	<tr>
		<th>
			<label for="user_street_address_line_1">Street Address (Line 1): </label>
		</th>
		<td>
			<input name="user_street_address_line_1" id="user_street_address_line_1" value="' . 
			$user_street_address_line_1 . '" class="regular_text" type="text" />
		</td>
	</tr>
	
	<tr>
		<th>
			<label for="user_street_address_line_2">Street Address (Line 2): </label>
		</th>
		<td>
			<input name="user_street_address_line_2" id="user_street_address_line_2" value="' . 
			$user_street_address_line_2 . '" class="regular_text" type="text" />
		</td>
	</tr>
	
	<tr>
		<th>
			<label for="user_locality">City: </label>
		</th>
		<td>
			<input name="user_locality" id="user_locality" value="' . 
			$user_locality . '" class="regular_text" type="text" />
		</td>
	</tr>
	
	<tr>
		<th>
			<label for="user_region">State/Region: </label>
		</th>
		<td>
			<input name="user_region" id="user_region" value="' . 
			$user_region . '" class="regular_text" type="text" />
		</td>
	</tr>
	
	<tr>
		<th>
			<label for="user_postcode">Postcode: </label>
		</th>
		<td>
			<input name="user_postcode" id="user_postcode" value="' . 
			$user_postcode . '" class="regular_text" type="text" />
		</td>
	</tr>
	
	<tr>
		<th>
			<label for="user_country">Country: </label>
		</th>
		<td>
			<input name="user_country" id="user_country" value="' . 
			$user_country . '" class="regular_text" type="text" />
		</td>
	</tr>
	
	<tr>
		<th>
			<label for="user_note">Note: </label>
		</th>
		<td>
			<textarea name="user_note" id="user_note">' . 
			$user_note . '</textarea>
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

	/* Save Photo URL */
	$user_photourl = $_POST['user_photourl'];
	if (!empty($user_photourl)) update_user_meta($user_id, 'user_photourl', $user_photourl);
	else delete_user_meta($user_id, 'user_photourl');
	
	/* Save Organization */
	$user_organization = $_POST['user_organization'];
	if (!empty($user_organization)) update_user_meta($user_id, 'user_organization', $user_organization);
	else delete_user_meta($user_id, 'user_organization');
	
	/* Save Job Title */
	$user_job_title = $_POST['user_job_title'];
	if (!empty($user_job_title)) update_user_meta($user_id, 'user_job_title', $user_job_title);
	else delete_user_meta($user_id, 'user_job_title');
	
	/* Save Note */
	$user_note = $_POST['user_note'];
	if (!empty($user_note)) update_user_meta($user_id, 'user_note', $user_note);
	else delete_user_meta($user_id, 'user_note');
	
	/* Save Phone (Work) */
	$user_phone_work = $_POST['user_phone_work'];
	if (!empty($user_phone_work)) update_user_meta($user_id, 'user_phone_work', $user_phone_work);
	else delete_user_meta($user_id, 'user_phone_work');
	
	/* Save Phone (Mobile) */
	$user_phone_mobile = $_POST['user_phone_mobile'];
	if (!empty($user_phone_mobile)) update_user_meta($user_id, 'user_phone_mobile', $user_phone_mobile);
	else delete_user_meta($user_id, 'user_phone_mobile');
	
	/* Street Address Line 1 */
	$user_street_address_line_1 = $_POST['user_street_address_line_1'];
	if (!empty($user_street_address_line_1)) update_user_meta($user_id, 'user_street_address_line_1', $user_street_address_line_1);
	else delete_user_meta($user_id, 'user_street_address_line_1');
	
	/* Street Address Line 2 */
	$user_street_address_line_2 = $_POST['user_street_address_line_2'];
	if (!empty($user_street_address_line_2)) update_user_meta($user_id, 'user_street_address_line_2', $user_street_address_line_2);
	else delete_user_meta($user_id, 'user_street_address_line_2');
	
	/* City */
	$user_locality = $_POST['user_locality'];
	if (!empty($user_locality)) update_user_meta($user_id, 'user_locality', $user_locality);
	else delete_user_meta($user_id, 'user_locality');
	
	/* State/Region */
	$user_region = $_POST['user_region'];
	if (!empty($user_region)) update_user_meta($user_id, 'user_region', $user_region);
	else delete_user_meta($user_id, 'user_region');
	
	/* Postcode */
	$user_postcode = $_POST['user_postcode'];
	if (!empty($user_postcode)) update_user_meta($user_id, 'user_postcode', $user_postcode);
	else delete_user_meta($user_id, 'user_postcode');

	/* Country */
	$user_country = $_POST['user_country'];
	if (!empty($user_country)) update_user_meta($user_id, 'user_country', $user_country);
	else delete_user_meta($user_id, 'user_country');
	
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
	require_once('hcard-vcard-shortcode.php');
}

add_action('init', 'init_hcard_vcard_generator', 1);

?>
