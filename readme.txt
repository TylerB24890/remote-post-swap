=== Remote Post Swap ===
Contributors: TyB
Tags: remote, database, posts, swap, post swap, remote database, wp-api, rest api, api
Requires at least: 4.7.0
Tested up to: 4.7.3
Stable tag: 0.5.0

Swap local development post data out with live/remote post data on the fly

== Description ==
This plugin was built for developers to swap out local post data with post data from a live site. Useful if you need to make some styling changes to post content but don't want to pull in the live database. Simply enter the URL for the live site and turn on the API connection via the toggle provided.

All posts within the loop are replaced with posts from the URL you entered. This will change out the post title, post content, post excerpt and post date with the returned API data. This plugin will **NOT** replace your the post data in the database, but simply change out the data on the fly. To go back to your the posts saved locally simply uncheck the toggle switch on the settings page.

Transients are used to store post IDs returned from the API call and matched up with the post IDs returned from your loop. This helps keep posts consistent across page loads. Single post pages will return the same post they were swapped out with in the main loop.

**NOTE:** This plugin requires the parent site (the live site) to be running at least WordPress Version 4.7.0 for the REST API to work properly.

== Installation ==

1. Upload the `remote-post-swap` directory to your `/wp-content/plugins/` directory.
2. Activate the plugin.

== Usage ==
1. After plugin activation go to the plugin settings page by hovering over the "Settings" menu item in wp-admin.
2. Click on the 'Remote Post Swap' link.
3. Check the toggle box shown.
4. Enter the URL to the live site you wish to pull posts from. This should be a valid URL.

== Changelog ==

= 0.5.0 =
* Initial Release
