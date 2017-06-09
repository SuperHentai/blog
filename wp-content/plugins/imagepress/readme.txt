=== ImagePress ===
Contributors: Ciprian Popescu
Tags: image, user, upload, gallery, album, category, profile
License: GPLv3
Requires at least: 4.0
Tested up to: 4.2
Stable tag: 5.4

== Description ==

Create a user-powered image gallery or an image upload site, using nothing but WordPress custom posts. Moderate image submissions and integrate the plugin into any theme.

== Installation ==

1. Upload the 'imagepress' folder to your '/wp-content/plugins/' directory
2. Activate the plugin via the Plugins menu in WordPress
4. A new ImagePress menu will appear in WordPress with options and general help

== Changelog ==

= 5.4 =
* FEATURE: Added collections module (BETA)
* FIX: Numerous bug fixes

= 5.3 =
* FIX: Fixed path checking for imagepress.css
* FIX: Added missing translatable strings to PO file
* IMPROVEMENT: Added new user default role check
* IMPROVEMENT: Removed all login cookie functionality and allow WordPress to handle it

= 5.2 =
* FIX: Fixed deprecated argument WP_User->id()
* FIX: Fixed deprecated get_postdata()
* FIX: Removed all console errors
* FIX: Fixed custom code for Critique/WIP icons
* IMPROVEMENT: Added "Image uploaded" and "Click here to view your image" as configurable labels
* IMPROVEMENT: Allowed imagepress.css to take precedence
* IMPROVEMENT: Added thumbnail for backend tables

= 5.1.2 =
* FEATURE: Added custom image sizes for portfolio themes
* IMPROVEMENT: Added better documentation and merged the author code
* IMPROVEMENT: Added better installation steps and amended the installation tab

= 5.1.1 =
* FEATURE: Overhauled profile editor (tabbed view)
* FEATURE: Added portfolio customization and themes
* FIX: Fixed/merged old functions
* FIX: Fixed image upload for profile editing
* IMPROVEMENT: Removed colour picker
* IMPROVEMENT: Removed colour options for top image

= 5.1 =
* IMPROVEMENT: New pagination system with live sorting and filtering
* IMPROVEMENT: New welcome box (with installation status and quick links)
* PERFORMANCE: Added item visibility features (only visible images load in browser)
* FIX: Fixed a PHP function not working in PHP 5.3
* I18N: Removed incomplete it_IT translation

= 5.0-beta4 =
* FIX: Multiple fixes and improvements

= 5.0-beta3 =
* IMPROVEMENT: Removed a link title from the users screen (images column)
* IMPROVEMENT: Removed an unused global variable ($wp_roles)
* IMPROVEMENT: Added image details and related posters as a function (see updated single-image.php)
* IMPROVEMENT: Removed 'cinnamon_card_hover' option
* IMPROVEMENT: Added more labels (notification related)
* FIX: Removed a deprecated call to "caller_get_posts"
* FIX: Added missing default labels for the voting module
* FIX: Added missing default labels for the notifications module
* FIX: Fixed image editing mode discarding current featured image
* FIX: Fixed new image addition notification
* FIX: Fixed duplicate notifications
* FEATURE: Added sorting and filtering for author cards (default onclick sorting is DESC)
* I18N: Added more translated strings

= 5.0-beta2 =
* FIX: Renamed the secondary upload function
* FIX: Fixed the PHP context error

= 5.0-beta1 =
* FIX: Check for slug and set a default value if empty
* FIX: Make slug field required
* FIX: Prevented event propagation from 'like' button
* CLEANUP: Removed lightbox script
* CLEANUP: Removed slider script
* IMPROVEMENT: Profile redesign

= 4.2 =
* I18N: More internationalized strings
* PERFORMANCE: Converted lightbox images to dataURIs
* FIX: Fixed option to enable Disqus integration (URL anchor append)

= 4.1 =
* FEATURE: Added option to make the description field mandatory
* FEATURE: Added option to override the WordPress default email notification
* FEATURE: Added option to enable Disqus integration (URL anchor append)

= 4.0-BETA2 =
* UPDATE: Updated profile page
* UPDATE: Cleaned up CSS file
* UPDATE: Updated single image view
* FIX: Fixed avatar size

= 4.0-BETA1 =
* FIX: Fixed empty title submission
* FEATURE: Added HTML5 filetype validation
* FEATURE: Added option to hide tags dropdown

= 4.0-RC3 =
* FIX: Added empty label check for author tools
* FIX: Added default styles for author tools (some themes were overriding them)

= 4.0-RC2 =
* FEATURE: Added installation steps
* FEATURE: Added upload fields customization
* UPDATE: Added contextual help
* UPDATE: Added missing shortcode to dashboard
* UPDATE: Overhauled profile page

= 4.0-RC1 =
* UPDATE: Merged Cinnamon Users
* UPDATE: Major overhaul

= 3.5 =
* UPDATE: Major update (lots of new features and rewritten functions)

= 3.2.1 =
* UPDATE: Code cleanup

= 3.2 =
* ADD: Added new image size (based on personal project)
* FIX: Fixed image link when integrated lightbox was active and attachment link was set
* FIX: Removed missing 2x images from the integrated lightbox
* UPDATE: Added more clarification to lightbox options
* UPDATE: Added more clarification to image size options
* UPDATE: Updated image URL text field as "url" field
* IMPROVEMENT: Added CSS3 box sizing for all ImagePress elements
* IMPROVEMENT: Moved all external lightbox to plugin folder
* IMPROVEMENT: Optimized all local images
* IMPROVEMENT: Optimized CSS3 masonry code with more browser-specific declarations
* IMPROVEMENT: Merged and minified CSS styles

= 3.1 =
* VERSION: Added WordPress 3.9 compatibility
* FIX: Fixed extra styles
* FIX: Added a missing shortcode and removed obsolete scripts from the dashboard page
* IMPROVEMENT: Removed Colorbox dependency and added custom lightbox based on Nivo

= 3.0 =
* FEATURE: Complete rewrite of plugin engine

= 2.7 =
* FIX: Removed the buggy frontend user table (administration is only available from the backend)
* FIX: Added correct placeholders for name and email fields
* FIX: Fixed configurator line breaks
* FEATURE: Added thumbnail size to the configurator
* FEATURE: Added maintenance options (reset all votes)
* IMPROVEMENT: Replaced image icons with FontAwesome icons
* IMPROVEMENT: Removed several unused/redundant CSS styles
* IMPROVEMENT: Removed 4 unused/redundant images
* IMPROVEMENT: Removed 2 unused/redundant JS files

= 2.6 =
* FIX: Sorting and filtering
* FIX: Count parameter
* FEATURE: Added filtering by user ID
* FEATURE: Added Configurator (enable/disable any line inside the image box)
* FEATURE: Added CSS transitions instead of jQuery (isAnimated Masonry parameter)
* REMOVE: Removed "url" parameter (use Configurator)
* REMOVE: Removed PressTrends tracking (better plugin performance)
* REMOVE: Removed Modernizr (better plugin performance)
* UPDATE: Combined 2 Javascript dependencies (better plugin performance)
* UPDATE: General code cleanup

= 2.5.2 =
* FIX: Allow multiple category shortcodes on the same page

= 2.5.1 =
* FIX: Fixed duplicated MP6 icon
* IMPROVEMENT: Moved settings menu to custom post menu
* IMPROVEMENT: All hardcoded submissions now use the category slug (please update)

= 2.5 =
* FIX: Removed styling for file input (100% mobile compatibility)
* FIX: Removed autofocus attribute as it was conflicting with theme features
* FIX: Added current selected user (filter)
* FIX: Removed author archive filtering
* FIX: Fixed a wrong label in plugin's settings
* IMPROVEMENT: Switched hardcoded category as a shortcode parameter instead of a global option
* IMPROVEMENT: Added updated code to single-user_images.php and documentation file
* IMPROVEMENT: File cleanup (removed 3 unused files)
* FEATURE: Added PressTrends tracking
* FEATURE: Added user gallery on click (click on username, just like Deviant Art)
* FEATURE: Caption is now a required field (HTML5 "required" attribute)
* UPDATE: Update translations (both plugin and single template file)
* REMOVE: Removed comments bubble as it did not count third-party comments and it was heavily dependent on cache
* REMOVE: Removed placeholder compatibility for IE (just uncomment the code in js/main.js if you want to use it)

= 2.4 =
* UI: Added dedicated MP6 dashboard icon (dashicon)
* UI: Merged dashboard with the settings area for easier access
* FEATURE: Image uploads now add authors as subscribers
* FEATURE: Added option to hardcode a category
* FEATURE: Added option to show or hide the category dropdown
* FEATURE: Added URL address field (as a shortcode parameter)
* FEATURE: New "Sort by author" dropdown function, now showing only users with images
* IMPROVEMENT: Removed Formalize plugin
* IMPROVEMENT: Removed hardcoded jQuery plugin

= 2.3.4 =
* FIX: Some servers add a paragraph break inside inline generated JS; it is now fixed

= 2.3.3 =
* FEATURE: Added author archive filtering
* FEATURE: Added author sorting

= 2.3.2 =
* FIX: Form accesibility improvements
* PERFORMANCE: Removed a useless/duplicate .js script

= 2.3.1 =
* FEATURE: Responsive top image (hall-of-fame) shortcode parameter

= 2.3 =
* FIX: Fixed registration condition
* FEATURE: Added top image (hall-of-fame) (based on views)
* FEATURE: Added top image (hall-of-fame) (based on votes)
* FEATURE: Added most viewed images widget
* FEATURE: Added most voted images widget
* FEATURE: Added time (in hours) before voting is possible again
* IMPROVEMENT: Moved form labels to option group instead of .po file
* IMPROVEMENT: Added IE placeholder fix
* DOCUMENTATION: Added custom post type template code sample

= 2.2.1 =
* FIX: Fixed a mispositioned curly brace

= 2.2 =
* IMPROVEMENT: Modernizr jQuery code now loads faster
* IMPROVEMENT: Fixed Lazy Load 0.5 plugin conflict (http://wordpress.org/plugins/lazy-load/) (thanks Jack Woodhams)

= 2.1.1 =
* IMPROVEMENT: Category sorter is now hierarchical

= 2.1 =
* FEATURE: Added image views counter
* FEATURE: Added image voting feature
* FEATURE: Added category sorter
* UI: Realigned image box bottom line
* UI: Backend tweaks
* BEHAVIOUR: Modified a shortcode to include another one (basically merged 2 shortcodes for better flexibility)

= 2.0.3 =
* FEATURE: Added option to set image link to either media or custom post type

= 2.0.2 =
* UI: Dashboard page tweaks
* UI: Icon tweaks
* UI: MP6 theme improvements
* UI: Added pagination CSS styles
* FIX: Fixed a reversed file_exists() function (imagepress.css)
* FIX: Fixed a script rendering error, blocking Masonry plugin
* FIX: Fixed official support link

= 2.0.1.1 =
* Added load_plugin_textdomain() function (thanks Andrea Cavaliero)
* Added it_IT translation (thanks Andrea Cavaliero)
* Added override stylesheet option
* Added image description
* Added single image template sample inside the documentation folder

= 2.0.1 =
* Added better image upload button (using jQuery)
* Added autofocus to image caption (using jQuery)
* Tweaked form UI
* Fixed an aggressive trim function
* Replaced an echo function with a return function
* Removed extra (useless) bootstrap styles (huge conflicts with some themes)

= 2.0 =
* Added notification email (on image upload, for administrator)
* Added notification email (on image approve/reject, for registered users)
* Added text colour option
* Fixed a missing menu slug
* Corrected several typos
* Corrected plugin license
* Renamed several backend menu slugs
* Small UI changes

= 1.0 =
* First public release
