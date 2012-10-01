=== hCard & vCard Generator ===
Contributors: jkohlbach
Donate link: http://www.codemyownroad.com
Tags: hcard, vcard, users
Requires at least: 3.0
Tested up to: 3.4.2
Stable tag: trunk

Given a user ID, this plugin will generate appropriate microformat compatible hCard and downloadable vCard formats for users.

== Description ==

The hCard & vCard Generator plugin was created to solve one specific problem, generating microformats compatible hCards from WordPress users and companion vCards for downloading purposes.

The plugin also beefs up the user profile section with new fields for organisation, job title, phone and mobile numbers

== Installation ==

1. Upload the plugin to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Place `<?php echo generate_card($userID, $cardType); ?>` in your templates where $userID is the user's ID and $cardType is either "hCard" or "vCard", or use the provided hCard vCard widget in the sidebar, or thirdly you can use shortcode to insert into posts/pages. See below:

For example to generate the hCard for admin use:
`<?php echo generate_card(1, 'hCard'); ?>`

To generate the vCard for admin use:
`<?php echo generate_card(1, 'vCard'); ?>`

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
ADR
END:VCARD

hCard Format:
`<div class="vcard">
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
</div>`

== Changelog ==

= 1.9 =
* Fixing HTML formatting bugs (thanks R. Richard Hobbs)

= 1.8 =
* Changed URL of photo to use WPURL instead of URL for installations that have 
moved their WordPress install to a subdirectory. Also added PHOTO and NOTES support.

= 1.7 =
* Adding .htaccess to force mime type of .vcf

= 1.6 =
* Implement addresses

= 1.5 =
* Bug fixes

= 1.4 =
* Improve title generation to allow non-auto generated or blank headings (thanks Kevin)

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
