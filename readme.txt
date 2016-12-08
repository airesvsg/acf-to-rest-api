=== ACF to REST API ===
Contributors: airesvsg
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=airesvsg%40gmail%2ecom&lc=BR&item_name=Aires%20Goncalves&no_note=0&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHostedGuest
Tags: acf, api, rest, wp-api, wp-rest-api, json, wp, wordpress, wp-rest-api
Requires at least: 4.3
Tested up to: 4.7
Stable tag: 2.2.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Exposes Advanced Custom Fields Endpoints in the WP REST API v2

== Description ==
Exposes [Advanced Custom Fields](https://wordpress.org/plugins/advanced-custom-fields/) Endpoints in the [WP REST API v2](https://wordpress.org/plugins/rest-api/)

**See details on GitHub:** http://github.com/airesvsg/acf-to-rest-api

== Installation ==
1. Copy the `acf-to-rest-api` folder into your `wp-content/plugins` folder
2. Activate the `ACF to REST API` plugin via the plugin admin page

== Changelog ==

= 2.2.1 =
bugfix options page id

= 2.2.0 =
get specific field via endpoints

= 2.1.1 =
bugfix term endpoint

= 2.1.0 =
adding rest base support

adding new filter acf/rest_api/default_rest_base

= 2.0.7 =
bugfix when create a new item

= 2.0.6 =
removing unnecessary code

= 2.0.5 =
changing how to check dependencies

= 2.0.4 =
fixed error when you register new fields ( via php ) and try save them

= 2.0.3 =
error fixed when register a new post with acf fields

= 2.0.2 =
adding support for options page ( add-on )

= 2.0.1 =
Bugfix strict standards

= 2.0.0 =
New version of the plugin ACF to WP REST API

Changing name ACF to WP REST API > ACF to REST API

== Upgrade Notice ==

= 2.0.0 =
This version enables editing of the ACF fields with WordPress REST API.