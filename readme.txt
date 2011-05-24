=== hCard & vCard Generator ===
Contributors: jkohlbach
Donate link: http://www.codemyownroad.com
Tags: hcard, vcard, users
Requires at least: 3.0
Tested up to: 3.1
Stable tag: trunk

Given a user ID, this plugin will generate appropriate miniformat compatible hCard and downloadable vCard formats for users.

== Description ==

The hCard & vCard Generator plugin was created to solve one specific problem, generating mini formats compatible hCards from WordPress users and companion vCards for downloading purposes.

The plugin also beefs up the user profile section with new fields for organisation, job title, phone and mobile numbers

== Installation ==

1. Upload the plugin to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Place `<?php generate_card($userID, $cardType); ?>` in your templates where $userID is the user's ID and $cardType is either "hCard" or "vCard", or use the provided hCard vCard widget in the sidebar, or thirdly you can use shortcode to insert into posts/pages. See below:

For example to generate the hCard for admin use:
`<?php generate_card(1, 'hCard'); ?>`

To generate the vCard for admin use:
`<?php generate_card(1, 'vCard'); ?>`

Alternatively, as of version 1.2 you can display the widget via shortcode:
`[hcardvcard title="Testing Title" user="1" display_vcard=true display_hcard=true]`

All attributes for the shortcode are optional, the current author gets used if no value is given.

== Frequently Asked Questions ==

N/A

== Screenshots ==

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
`<div class="vcard">
	<span class="fn">Forrest Gump</span>
	<a class="url" href="http://example.com">http://example.com</a>
	<span class="tel">
		<span class="type">Work</span>:
		<span class="value">+1.415.555.1212</span>
	</span>
</div>`

== Changelog ==

= 1.3 =
* Fixing bug with vCard generation (thanks Thomas)

= 1.2 =
* Overhauled widget
* Added shortcode display
* Fixed bug in generation due to incorrect folder check

= 1.1 =
* Updating calls to WP 3.1 standard. Fixing bugs with the widget.

= 1.0 =
* Initial version

== Upgrade Notice ==

N/A
