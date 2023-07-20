=== BioShip ===
Contributors: majick
Tags: one-column, two-columns, three-columns, left-sidebar, right-sidebar, buddypress, custom-background, custom-colors, custom-header, custom-menu, editor-style, featured-images, flexible-header, post-formats, theme-options, threaded-comments
License: GPLv2 or later
License URI: http://www.opensource.org/licenses/gpl-license.php
Donate Link: https://wordquest.org/contribute/
Requires at least: 3.4
Requires PHP: 5.3
Tested up to: 6.2.2
Stable tag: trunk

BioShip is an extended Hybrid Skeleton Theme Framework. Responsive, flexible, cross-browser friendly, easily skinnable and customizable.

== Description ==

BioShip aims to be a flexible and performant child theme framework capable of adapting to ANY design and development requirements.

Firstly it is a "blank canvas" or "skeleton" theme that focusses on including ALL the basic theme necessities - and getting them right - making it very easy to skin quickly for end users and designers.

At the same time, it includes extensively tested code under the hood to make it highly flexible for developers, with extended file and template hierarchies, layout position hooks and custom theme value filters.

Other baked in features include one-click child theme creation, responsive grid system, flexible multiple sidebar options, Schema.org markup, cross-browser friendliness, theme setting import/export, custom post type support, integrations and much more.

It is the stable end result of years of coding, experimentation, improvements, testing, streamlining, refactoring and retesting on a wide variety of different live sites.

Plus it is now fully documented (with source code commented) for your reference, so you can confidently create the site that you want.


== Frequently Asked Questions ==

= So how do I...? =

Please see the full documentation at https://bioship.space/documentation/
Or click the Docs link on your Theme Options page to view them in your WordPress Admin area.


== Upgrade Notice ==

= 2.2.0 =
* Major overhaul update with multiple fixes and improved standards


== Screenshots ==


== Changelog ==

= 2.2.1 =
- Updated: Freemius SDK 2.5.10
- Fixed: PHPMailer class not found (WP class_alias failure)
- Fixed: mismatched label for post type object author display
- Changed: prefix admin javascript functions

= 2.2.0 =
- Updated: Freemius SDK to 2.4.3
- Updated: WordQuest Helper Library to v1.8.2
- Disabled: Kirki (further update configuration testing needed)
- Disabled: Split Customizer options (conflict testing needed)
- Removed: Customizer Controls library (not used)
- Removed: Foundation library (not used)
- Feature: Admin Bar Style Editor dropdown (sitewide/admin/perpost)
- Improved: set default subsection fonts to inherit color and size
- Improved: group included files for plugins in template dropdown
- Improved: integration loader and separate integrations directory
- Added: Code Editor for admin option page and styles dropdown box
- Added: login wrap box text colour styling option
- Added: permission note to title attribute of edit post link
- Added: first and last menu item classes to navigation items
- Added: sticky navigation bar and sticky logo options
- Added: reseter.css option to reset modern CSS browser styles
- Added: filter for main menu mobile button icon to replace text
- Fixed: main menu frontend display disappearing
- Fixed: autospace main menu items count styling
- Fixed: footer widget areas count to allow for empty areas
- Fixed: redeclare wpColorPickerL10n variable for WP 5.5+
- Fixed: Theme Info page redirection on activation slug mismatch
- Fixed: load admin page styles on standalone Theme Info page
- Fixed: thumbnail display comment string append typo
- Fixed: possible function redeclarations in skin.php loading
- Fixed: possible mixed content for image protocols in skin.php
- Fixed: metabox per post CSS quicksave message ID for display
- Fixed: jQuery version detection for jQuery via Google CDN option
- Fixed: missing theme override global for sidebar columns width
- Fixed: check if FS_CHMOD_FILE is defined when File System writing
- Fixed: category list meta format for non-post CPT taxonomies
- Fixed: fallback / empty / filtered sidebar templates for CPTs
- Fixed: possible endless loop in get_the_excerpt shortcode
- Fixed: primary navigation menu ID attribute (primarymenu)
- Fixed: button styles affecting admin button styles
- Fixed: integer detection in debug logging function
- Fixed: font size conversion applying to em when already em
- Fixed: include Block and Block Parser for skin.php direct load
- Fixed: display comment and reply link/buttons as inline blocks
- Fixed: missing javascript object prefix for fitvidselements
- Fixed: image size theme options for post list displays
- Fixed: declaration of post thumbnail theme support for CPTs
- Fixed: Customizer item spacing for Advanced/All Options pages
- Fixed: file edit link to Parent template in templates dropdown

= 2.1.4 =
- Updated: WordQuest Helper Library to v1.7.5
- Added: accessibility skip link in header.php (just after wp_body_open)
- Moved: Theme Options Editor Metabox to admin/metabox.php (from admin.php)
- Improved: add file structure notes to all main theme function files
- Improved: add option to show thumbnail in admin column for any CPTs
- Improved: move selective resource build save triggers internally
- Fixed: admin post thumbnail column image display for post lists
- Fixed: duplicate title/description output for home blog page archive
- Fixed: missing archive pagination for blog page (as front or blog page)
- Fixed: various escape output messages throughout theme functions
- Fixed: first comment meta spacing for buttons (to align with threaded)
- Fixed: documentation popup thickbox links on Info Settings tab
- Fixed: documentation includes for hooks and theme options pages

= 2.1.3 = 
- Added: license info and copy of GNU Public Licence 3 (license.txt)
- Added: resources copyright and license list (/includes/licenses.txt)
- Added: Hover Intent script (jquery.hoverIntent.js) for Superfish
- Updated: all scripts to include minified and non-minified versions
- Updated: WordQuest Helper Library to v1.7.4
- Updated: reset stylesheet (reset.css) to v2
- Updated: Fastclick script (fastclick.js) to v1.0.6
- Updated: Scroll to Fixed script (jquery.scrolltofixed.js script to v1.0.7
- Updated: Match Height script (jquery.matchHeight.js) to v0.7.2
- Updated: PrefixFree script (prefixfree.js) to v1.0.10
- Updated: NWMatcher script (NWMatcher.js) to v1.4.4
- Updated: FitFids (jquery.fitvids.js) to v1.2
- Updated: Superfish script (superfish.js) to v1.7.10
- Updated: Normalize style (normalize.css) to v8.0.1
- Improved: optimized main theme script code (bioship-init.js)
- Improved: honour SCRIPT_DEBUG constant to use unminified scripts
- Improved: added default body font (for fonts with inherit rule)
- Improved: prefix all theme javascript functions (prefix all the things)
- Renamed: csshero.js to bioship-csshero.js (prefix all the things)
- Moved: Admin Bar Theme Options Item to muscle.php for frontend
- Removed: options-custom.js (old Options->Titan tab patch script)
- Removed: smoothjump.js (single function already incorporated)
- Removed: sticky-widgets.css file (for sticky admin widgets)
- Removed: docready.js file (not implemented for anything)
- Fixed: themesettings variable for get header image size (skin.php)
- Fixed: moved Sticky Kit load to head (footer load not working)
- Fixed: Admin Bar Theme Options icon style for frontend 
- Fixed: mismatching function name for Titan nonce refresh
- Fixed: removed duplicate output escaping in archive templates
- Fixed: moved no content paragraph wrapper in error template

= 2.1.2 =
- Added: readme.txt for wordpress.org theme repository (merged changelog.txt)
- Added: call new wp_body_open hook in header.php (with backwards compatibility)
- Added: basic Elementor Locations support (header/footer/archive/single)
- Added: missing menu override output filters (primary/header/footer)
- Added: missing output echo escapes in overall theme files
- Added: phpcs:ignore comment flags when echoing already escaped variables
- Added: some missing button text translation wrappers
- Updated: TGM Plugin Activation v2.6.1 (pre-configured for wordpress.org)
- Updated: WordQuest Helper to v1.7.3 (reminder notice capability fix)
- Fixed: re-added Titan web safe font filter (removed from Titan Framework)
- Fixed: use of duplicate variable for typography in skin loader
- Fixed: Inline Styles Setting output exiting early bug
- Fixed: incorrect directory variable for Titan Framework checker
- Fixed: added dummy func_get_args for backwards compatibility for PHP < 5.3
- Fixed: replaced round half down with standalone function for PHP < 5.3
- Fixed: added check for edit theme permissions to Admin Bar Theme Options item
- Fixed: removed incompatible Hybrid term list usage from taxonomy meta display 
- Fixed: Titan options CSS quicksave settings message display and fadeout
- Fixed: removed breaking double quotes around inherit font value output
- Improved: changes .site-desc span to div to allow text width wrapping
- Improved: active widget counting in footer sidebar template (sidebar/footer.php)
- Removed: misplaced custom.js custom menu script (from BioShip theme site)
- Removed: file changelog.txt (merged with readme.txt)

= 2.1.1 =
- Updated: Full Code Review of all core theme files!
- Updated: Documentation to match previous version changes
- Updated: translations file (/languages/bioship.pot)
- Changed: moved template includes tracing functions to tracer.php
- Changed: moved skin loading functions from functions.php to skull.php
- Changed: skeleton_navigation_hide filter to skeleton_navigation_remove
- Fixed: lots of minor bugs found during code review
- Fixed: PerPost Theme Options Metabox saving keys (newly prefixed)
- Fixed: possible error in file modified time cachebusting conditionals
- Fixed: stop Titan trying to load webfont stacks via Google Fonts
- Fixed: removed all line breaks from dynamic editor styles
- Fixed: typo cutting off comment styles in skeleton.css
- Fixed: Hybrid Cleaner Gallery minified stylesheet loading
- Fixed: add missing heading styles to Dynamic Editor Styles
- Fixed: theme file settings backup file() read for unserialization
- Optimized: moved all trigger checks inside functions for consistency
- Optimized: moved all add_actions inside function exists for consistency
- Optimized: page template includes indexing and admin bar display names
- Added: Theme Options Metabox quicksave override settings buttons
- Added: separated file admin/tools.php for Theme Tools functions
- Added: allow for alternative include directory path
- Added: extra (optional) value argument to bioship_apply_filters
- Added: single action or template tracing to theme tracer

= 2.1.0 = 
- Updated: Titan Framework to v1.12
- Added: Cookie field to comment form for GDPR Compliance
- Fixed: use existing multicheck settings if empty (super-bug!)
- Fixed: index array check for stored global menus
- Fixed: incorrect default variable for disable emojis option
- Fixed: ignore font loading where font face is set to inherit
- Fixed: nonce key for Titan admin nonce cyclic auto-refresh
- Added: alert on session timeout on Titan theme options screen

= 2.0.9 =
- Updated: TGMPA Plugin Activation v2.6.0
- Updated: Freemius for Themes v7
- Updated: Titan Color Picker Alpha Script v2.1.3
- Updated: Kirki Library to v3.0.16
- Deprecated: Hybrid 2 for WordPress.org version
- Deprecated: Foundation 5/6 for WordPress.org version
- Removed: Dashboard Feed Widget for WordPress.org version
- Removed: Discreet Text Widget for WordPress.org version
- Option: navigation submenu hover color and background color
- Added: protype auto resize of site title text on window resize
- Added: Custom Background theme support for WordPress.org version 
- Added: Custom Logo theme support for WordPress.org version
- Added: get sidebar template header information function
- Added: missing subsidiary sidebar date archive template
- Added: Beaver Themer plugin integration (Hook definitions)
- Added: DNS preconnect resource hint for Google Fonts
- Added: autoload of matching child-theme-slug.php file
- Improved: child theme creation process for WordPress.org
- Improved: standardized all sidebar template headers
- Improved: theme hook information array and labeling
- Improved: delayed priority for bioship_remove_actions
- Improved: allow for theme debug display and/or logging
- Improved: use variables instead of inputs for script loading
- Improved: added value filter to theme file hierarchy result
- Fixed: Titan options panel font color and text-shadow inputs
- Fixed: missing some class attributes in sidebar templates
- Fixed: incorrect index for BWP script auto-ignore integration
- Fixed: set all font sizes in em for screen scaling
- Fixed: missing number range on jpeg quality option
- Fixed: missing email input type for CSS input styling
- Fixed: missing style targeting for registration form wrapper
- Fixed: typo in read more jump link removal filter
- Fixed: classes and typo for active menu item style rules
- Fixed: debug file writing append using WP Filesystem
- Fixed: documentation submenu link and redirection

= 2.0.8 =
- Changed: various WordPress.Org compliance changes
- Improved: Author Bio box to work outside of the loop
- Fixed: global Site Icon and Favicons conflict
- Fixed: full width content grid columns for mobile queries
- Fixed: Discreet Text Widget dependency on Text Widget
- Added: Classic Text Widget class for WP 4.8+
- Added: HTML Comments Output Function

= 2.0.7 = 
- Changed: /javascripts to /scripts (file hierarchy unchanged)
- Removed: unused adapt.js and adapt.min.js scripts
- Disabled: XML exporting removed as not importing
- Fixed: text domain variations to bioship text domain (+kirki)
- Fixed: some incorrect text domain typos
- Fixed: navigation menu text hover color targeting
- Fixed: inconsistent line breaks for foundation.selected.js
- Fixed: Theme Admin page notices output
- Fixed: Theme Settings file import bugs
- Added: serialized format Theme Settings export option
- Added: documentation link to admin menu

= 2.0.6 =
- Updated: screenshot.jpg with Demo Content for compliance
- Updated: added frame to Child Theme screenshot.jpg image
- Fixed: grid.php content columns querytstring setting typo
- Fixed: some missing apply_filters prefixes in options.php
- Improved: streamlined some existing skeleton style changes

= 2.0.5 = 
- Added: Freemius SDK Library for Themes 1.2.2.5
- Added: Auto-ignore core styles/scripts for BWP Minify
- Added: Matching translation POT file to /languages
- Added: Content repeater template for Ajax Load More
- Added: Tracer calls within Customizer functions
- Added: Tracer trace for any fired BioShip action hooks
- Updated: respaced comma spacing syntax in all functions/files
- Updated: check wp-load.php before using direct skin method
- Updated: all grid.php variables loaded from querystring
- Updated: backwards compatibility for old skeleton actions
- Changed: all remaining functions to bioship_ prefix
- Changed: Hybrid Hook theme prefix to bioship to match actions
- Changed: image size names to bioship- prefix 
- Improved: automatically regenerate thumbnails for new sizes
- Removed: unnecessary wp-load.php process from grid.php
- Removed: legacy skeleton theme template files
- Fixed: subsidiary sidebar class names mismatches
- Fixed: theme options display version for child theme
- Option: JPEG Quality filter to Muscle -> Thumbnails

= 2.0.2 = 
- Added: Admin Notice message helper function
- Added: Auto-ignore core styles/scripts for BWP Minify
- Added: THEMESLUG constant (for dashes not underscores)
- Updated: WordQuest Helper Library to 1.6.7
- Changed: all grid_ functions to bioship_grid_ prefix
- Fixed: some simplified script load check typos

= 2.0.1: =
- Fixed: title-tag (or no title-tag!) Theme Supports
- Changed: all skin_ functions to bioship_skin_ prefix
- Changed: all skeleton_ action hooks to bioship_ prefix
- Updated: theme slug prefix to script and style handles
- Updated: simplified some constant usage in theme setup
- Added: page template includes list dropdown to Admin Bar
- Added: sidebar-template class to sidebar elements
- Added: missing script loading conditional filters

= 2.0.0 =
- Updated: WordQuest Helper Library to 1.6.5
- Updated: BioShip news dashboard feed widget
- Added: missing translation wrappers to admin
- Fixed: full page content feed output
- Fixed: multicheck box options bug one more time
- Fixed: filemtime mode cachebusting for style.css
- Fixed: breadcrumb display override targeting bug
- Fixed: meta formatting duplicate dash replacement bug

= 1.9.9 =
- Customizer: split into Basic and Advanced Option pages
- Option: jQuery Match Height loading for .matchheight classes
- Added: function tracer lines to all templating functions
- Fixed: jQuery StickyKit and Mobile Queries conflict
- Fixed: jQuery StickyKit total width display glitch
- Fixed: set changed theme sidebar state for empty sidebars
- Removed: old unused display output override checks
- Optimized: reduced old meta formatting code bloat

= 1.9.8 =
- Fixed: map default options if empty (on theme activation)
- Fixed: fix to numerous undefined variable warnings
- Fixed: add check for logoresize page element in init.js
- Fixed: to TMGPA plugin recommendations array
- Fixed: process shortcodes in excerpts filter option
- Fixed: removed overflow:hidden from CSS clearfix causing height
- Improved: tracer.php fully revamped outdated debug tracer
- Improved: use templating action priorities from hooks array
- Update: replace deprecated constructor on Discreet Text Widget
- Update: Discreet Text Widget (deprecated constructor method)

= 1.9.7 =
- Hotfix: missing argument 2 for filter warning breaking headers

= 1.9.6 =
- * Bugfix Update *
- Docs: Grid System documentation completed
- Fixed: grid.php overflow bug by recalculating wrap width total
- Fixed: grid.php offset left class rule selector typo
- Fixed: muscle.php some mismatching WP to Open Graph locales 
- Fixed: skin.php login and inline admin styles output exit bug
- Fixed: skin.php missing fallback to main background for wp-login
- Fixed: skin.php missing number input type for input styles
- Fixed: options.php background repeat option array typo
- Fixed: javascript logo resize on pageload for smaller screens
- Fixed: theme options header size for smaller screen widths

= 1.9.5 =
- * Final Public Beta *
- Docs: Child Theme and Framework documentation completed
- Docs: Metabox and Sidebar documentation completed
- Improved: Grid System layout value filtering
- Improved: theme settings autobackup/restore process
- Improved: perpost metabox display tables and sections
- Standardized: global vthemeoptions is now vthemesettings
- Standardized: global vdisplayoverrides is now vthemedisplay
- Option: Standalone Content Grid Columns Default Value
- Option: load Dynamic Editor Styles to match theme settings
- Option: disable Emoji scripts and styles loading
- Added: Percentage Content Grids for 12/16/20/24 Columns
- Added: PerPost Sidebar Overrides Interface
- Added: PerPost CSS Styles QuickSave button
- Added: optional Archive Template Subdirectory to hierarchy
- Added: optional alternative template directory for WooCommerce
- Filter: alternative WooCommerce template directory
- Filter: separated sidebar hide and no output filters
- Changed: default javascript directory name to scripts
- Updated: Options to Titan Framework settings transfer
- Updated: Kirki Customizer Libary to 2.3.5
- Optimized: Content Grid compatibility classes
- Fixed: Post Type(s) Detection (to not use is_single)
- Fixed: insane settings saving bug on some old installs
- Fixed: Body Login class prefix to Admin Login Styles
- Fixed: dashes in Theme Mods slug for Autospace Main Menu
- Fixed: new child theme install destination directories
- Fixed: matching jQuery handle for CDN fallback script
- Fixed: remove unused CodeMirror scripts from Kirki load
- Fixed: theme settings user backup restore button action
- Fixed: apply_filters typo for archive content template
- Fixed: Matchmedia.js file hierarchy call argument
- Removed: [off] from sidebar labels (styling sufficient)
- Removed: old force update code for insane saving bug

= 1.9.0 =
- * Public Release Candidate *
- skull.php: moved skull functions from functions.php
- Optimized: Precalculate Filtered Theme Layout State
- Improved: Sidebar Template Hierarchy System
- Improved: Sidebar Registration, Order and Labelling
- Improved: Pass Filtered Theme Layout to grid.php
- Improved: Theme Options Page Colour Scheme
- Improved: standard vtheme prefix for theme globals
- Improved: Layout Sections and Hook Global
- Added: first/last/odd/even Widget Style Classes
- Added: more documentation... about halfways there
- Fixed: duplicate ID for new site-description attribute
- Fixed: allow blank (empty) Sidebars via filtering

= 1.8.5 =
- * Customizer Completion *
- docs.php: Dynamic Documentation Display (prototype)
- Added: Customizer Sidebar Controls (width, left/right)
- Added: Customizer Dynamic CSS Live Preview
- Added: Customizer Typography Live Preview (Titan)
- Added: Customizer Background Images Live Preview
- Added: Customizer Logo Image/Text Live Preview
- Added: Customizer Hover Selectors Live Preview
- Added: Customizer Button Gradients Live Preview
- Added: Customizer Sanitization fallbacks
- Added: Header Title Text and Description Filters
- Added: Import Theme Options via File Upload Method
- Added: Custom CSS QuickSave Button to Theme Options
- Added: Archive and Search Sidebar Widget Area
- Added: Universal get Post Types helper function
- Added: Base Template (header/footer/loop) Hierarchies
- Improved: Better CSS Hero Selector Declarations
- Improved: Header Background Image Size Caching
- Improved: Minor comments.php template tweaks
- Improved: Selective Theme Options import allowed
- Optimized: Conditional loading of superfish.js
- Optimized: Re-indexed Layout Hook/Label Reference
- Optimized: Much cleaner core loop templates
- Option: Category/Taxonomy/Tag/Author/404 Sidebars
- Option: Main Navigation Menu Styling Options
- Option: Resize Header Logo Image on Window Resize
- Option: AutoSpace Main Navigation Items
- Option: Input text and background colour selection
- Option: Apply Button Colours to WooCommerce Buttons
- Option: Apply Buttons to Comment Edit/Reply Links
- Option: Hybrid Breadcrumbs for Post/Archive Types
- Option: use File Modified Time for Cachebusting
- Option: RSS Feed Excerpt Length
- Option: Full Content RSS Feeds for Pages
- Option: Login Form Background Colour
- Metabox Option: hide page navi or breadcrumb trail
- Metabox Option: hide header menu or footer menu
- Metabox Option: no wrapper margins (full width screen)
- Moved: Login Options tab to Skin Layer (styling)
- Moved: admin.php, customizer.php, tracer.php to /admin/
- Removed: Default Navigation Styling from skeleton.css
- Removed: Grid Compatibility Classes from Template Columns
- Removed duplicate All Options global variable
- Renamed: /child-source/ directory to /child/ 
- Renamed: /css/ directory to /styles/
- Renamed: loop-hybrid.php is now loop-index.php
- Fixed: Options Framework Customizer Section bug
- Fixed: Options Framework/Titan Multicheck Conflict
- Fixed: Customizer Multicheck array saving (yeesh)
- Fixed: Theme Options Sidebar Save Button Event
- Fixed: Admin Theme Menu links for Theme Test Drive
- Fixed: nonce checks for options restore/import/revert
- Fixed: Login Page Logo and Login Form Styling
- Fixed: Page Navigation filters and display logic
- Fixed: Export Theme Options in XML format
- Fixed: Theme Options Sidebar Save Submit Selectors
- Fixed: Customizer Transport Refresh for Live Preview
- Fixed: Kirki control script URL misconfig bug
- Fixed: Extra Font selection array key bug
- Fixed: PerPost Thumbnail display override
- Fixed: Site Icon and Startup image bugs
- Fixed: Match WP jQuery version for Google CDN option

= 1.8.0 =
- * Major Beta Overhaul *
- customizer.php - Customizer Support Added (yeesh!)
-- added Kirki Customizer Control Library (helpful)
- csshero.js: CSS Hero Theme Declaration Support!
- Update: Hybrid Core Library to version 3!
-- loop-meta.php: use get_the_archive_title/description
-- options: removed old Hybrid Core 2 extensions
- Update: Titan Framework Options Conversion!
-- (maintains back-compatibility with Options Framework)
-- revamped Titan admin page to single page with layers
- Update: TGM Plugin Activation (to namespaced version)
- Update: Added Foundation 6.2 loading support integration
- Started: Microthemer Scaffold Declarations Support
- Moved: All Theme Options Interface functions to admin.php
- Moved: Child Theme Widget save/restore to admin.php
- Moved: PerPost Theme Option Metabox UI to admin.php
- Optimized: streamlined PerPost options to reduce queries
- Optimized: revamped Grid (margins to inner padding)
- Optimized: revamped sidebar display conditional logic
- Optimized: mobile button media display queries
- Optimized: mobile button functions (now jQuery)
- Optimized: improved file search hierarchy (+SSL Fix)
- Option: to Add Excerpt Support to Pages
- Option: Flexibility (polyfill) for IE8+9 Flexbox Support
- Option: to echo HTML Element Comment Wrappers or not
- Changed: % to # for Meta Format Tags (Titan conflict)
- Changed: Option to Filter to Load Theme Function Tracer
- Added: use WP Filesystem for Child Theme Creation
- Added: Frontpage and Home (Blog) Sidebars/Subsidebars
- Added: Theme Info section to Theme Options page
- Added: Theme Tools - Backup/Restore/Import/Export Interface
- Added: Media Handler for Attachments and Post Formats
- Added: Full width 'Banner' display positions
- Added: extra Customizer Control libararies
- Added: #content CSS class name filter
- Added: alpha omega grid classes to #content
- Added: Labels to Hook array for future feature
- Added: post content to top of 'Blog' (Page for Posts)
- Added: extra action hook for top of front page 'Blog'
- Added: AJAX Load More plugin Repeater to templates
- Fixed: SSL Recheck for Parent/Child URI Resources
- Fixed: CSS targeting to inside elements for typography
- Fixed: manage_options to edit_theme_options capability
- Fixed: some more missing translation wrappers
- Fixed: more undefined indexes and variable warnings
- Fixed: jQuery handle for Google CDN and fallback
- Fixed: admin resource URLs for Options Framework
- Fixed: meta format author display name fallbacks

= 1.5.5 = 
- Fixed: Text Domain translation strings for Theme Check
- Fixed: css #content column targeting in grid.php
- Fixed: remove mobile buttons to match perpost options
- Deprecated: muscle.php unworking post revision limit method

= 1.5.0 =
- * First Public Beta Release Version *
- Updated: Hybrid Core v2.0.2 to v2.0.4 (minor bugfixes)
- Updated: TGM Plugin Activation v2.4.0 to v2.5.2
- Updated: Formalize form element styling CSS to v1.2
- Updated: Overhauled thumbnail handling functions
- Updated: Lots of minor code and style improvements
- grid.php - new dynamic stylesheet for em based grid loading!
- index.php - now supports CPT header and footer templates
- skeleton.php - moved templating functions from functions.php
- admin.php - moved admin-only functions from functions.php
- skin.php - can now be called directly (uses Shortinit)
- options.php - settings page functions are now pluggable
- author-bio.php - moved template functions to functions.php
- content.php - moved thumbnail functions to functions.php
- content.php - added more template action hooks to fire
- content.php - added bio box top or bottom position calls
- comments.php - moved to /content/ and hierarchy filter added
- templates.php - added to Child Theme (small Template Guide)
- hooks.php - changed layout.php guide for use with Hybrid Hook
- Added: Mobile Buttons for main menu, sidebar and subsidebar
- Added: Hybrid Hook extension for adding content to hooks
- Added: Theme Tracer for template and function tracking 
- Added: Thumbnail Size Override to the PerPost Metabox
- Added: Browser/Mobile detection CSS filters for skin.php
- Added: Theme Update available display to Theme Options page
- Added: Extend layer tab to Theme Options for extensions
- Added: Import/Export Theme Options as XML (no UI yet)
- Added: filters to sidebar and layout to support CPT sidebars
- Added: YouTube fullscreen video background option (via filters.php)
- Added: Dashboard updates notice with Theme News Feed widget 
- Added: missing div comments and new lines for source readability
- Added: Woocommerce template hierarchy override filters
- Added: Some missing Muscle admin options filters
- Added: Theme icon next to Theme Options in Admin Bar
- Added: Meta replacement values for page/category parents
- Added: Filters for all hooked layout (priority) positions
- Added: Class filters for header, sidebars, content, footer
- Added: Fallback to local jQuery for Google jQuery CDN failure
- Fixed: use of getimagesize in skin.php for no allow_fopen_url
- Fixed: bug in subsidebar option ID for background colour 
- Fixed: default Wordpress admin menu CSS for Wordpress 4.0
- Fixed: WP version comparisons to use version_compare
- Fixed: a bunch of undefined index notices caught by debugger
- Fixed: thumbnail display option in content.php for Hybrid
- Fixed: comments template filter for parent theme fallback
- Fixed: PrefixFree and Google Fonts CORS conflict
- Fixed: whitespace conversion in Custom Font names
- Fixed: button styles now properly override Skeleton styles
- Fixed: Child Theme creation menus and menu locations
- Fixed: Child Theme Options page shows parent update available
- Fixed: Translations function now applied to all options texts
- Fixed: Titan Admin Page Current Option Tab Selection Saving
- Fixed: Smooth Scrolling JS Bug for jQuery 1.12 (WP 4.5)
- Option: Number of dynamic Grid Columns: 12, 16, 20 or 24!
- Option: 960 Grid System / Blueprint class compatibility
- Option: Multiple Media Query Breakpoints for dynamic Grid
- Option: Open Graph Protocol plugin default image options
- Option: Combine Core CSS to single file option
- Option: default thumbnail alignment class options
- Option: missing load FitVids option to Muscle Scripts tab
- Option: missing load Formalize option to Skin CSS tab
- Option: missing option for Smooth Scrolling Hash Links
- Option: Experimental Foundation loader options
- Option: timestamps to options array for backups/exports/imports
- Option: top or bottom author bio box position and filters
- Option: to show Theme Options link in admin bar or not
- Option: Options saves current option tab not just layer filter
- Option: missing option for no logo at all on wp-login.php page
- Option: for displaying Page Navigation for different post types
- Changed: default thumbnail size to 250 square for social sharing
- Changed: thumbnails and bio boxes are now called by action hooks
- Fixed: Hack to Appearance Theme Options submenu display position
- Deprecated: root page.php - index.php now handles all post types
- Deprecated: Browser Body Class function (page cache unfriendly)

= 1.4.5 =
- Pre-Release Conversion Testing Version
- Theme Updater! Added WShadow Theme Upgrade Checker 
- Added: Manual Backup/Restore of Theme Options
- Added: login page specific background image option
- Added: option for default Gravatar URL image handling
- Fixed: Theme Testdrive Theme Options Saving Bug (madness!)
- Fixed: post type selection and filtering of main RSS Feed 
- Fixed: is_search() check in content.php and aside.php page templates
- Fixed: admin login logo path size check on subdirectory installs
- Fixed: typo in skeleton_entry_footer_meta function
- Fixed: category list replacement value in post/page meta
- Improved: Home Blog Include/Exclude Category Option

= 1.4.0 =
- Added: CSS replacement values for theme image directory URLs
- Added: Title Font and Font Stack example display pages
- Added: button text filters to Theme My Login templates
- Added: load PrefixFree Support (Javascript) option
- Added: load NWMatcher CSS Selector (Javascript) option
- Added: load NWEvents Event Manager (Javascript) option
- Added: load CSS.Supports (Javascript) option
- Added: default Gravatar replacement option (gravatar.png)

= 1.3.5 =
- Added Post and List Thumbnail Size Selection
- Added default Thumbnail Cropping Options
- Added: Admin Logo to Theme My Login forms option
- Added: overrideable Child Theme logo image
- Option: Autoload for Extra Custom Fonts (via Google Fonts)
- Option: Cleaner Admin Bar (removes Wordpress links)
- Fixed: Wordpress admin area display tweaks
- Fixed: Hybrid image size post editor conflict

= 1.3.0 =
- Added: One-Click Child Theme Installation!
- Added: layout.php to Child Theme (page elements)
- Added: CSS/JS Cache Busting options
- Improved: Theme Test Drive Compatibility

= 1.2.5 =
- Added: filters.php to Child Theme (value filters)
- Added: Form Button Colour Options
- Added: Theme My Login Image Button support
- Fixed: CSS stylesheets and dependencies

= 1.2.0 =
- General bugfixes and improvements
- Dual Header Background and Header Logo
- Added majority of Muscle functions
- Improved Skeleton and Skin functions

= 1.1.0 =
- Major theme development and coding work
- Modifications *WAY* too numerous to note here!
- Convert Skeleton to Skin/Muscle/Skeleton

= 1.0.0 =
- Converted from Skeleton Child to Parent Theme

= 0.1.0 =
- * Minor Update and Fix List *
- Retest Widget Transfers on Activation/Deactivation
- Retest Pagination Display for Post/Archive Types
- Recheck Editor Styles against core Theme Styles
- Check Image URL(s) on Save: not found to Admin Notice?
- Resource Logging: any files not found to Admin Notice?

= 0.0.5 =
- ** Major Update and Testing List **
- ReTest MultiSite Compatibility
- Test/Improve Post Format Media Templates
-- Test Hybrid Post Format Filter extension
- Test Import/Export while Theme Test Driving
- Check Translation Support (for WMPL)

= 0.0.3 =
- ** Customizer List **
- Test Grid Option Changes in Live Preview
- Sidebar Controls (allow top/bottom position)
- ? PerPost Theme Options context panel
- ? Custom Control for Media Upload *OR* URL

= 0.0.2 =
- ** Planned Option List **
- Select CPTs to Display Theme Options Metabox on
- Author Bio Display Position on Author Archives
- Options Framework: rgba colour picker control ?
- ? Set Image Quality Filter wp_editor_set_quality
- ? Disable Hybrid Schema.Org Attribute Markup 
- ? Separate Admin Style CSS Loading Mode
- ? Foundation Grid-only Loading
- ? Titan control for Media Upload *OR* URL

= 0.0.1 =
- ** Planned Feature List **
- Theme Settings Revisions via Changesets
- Floating/Fixed 'Back to Top' scroll button
- Allow for Paged Templates in Hierarchy
- Selective Foundation 6 Loading Support
- ? Colour Presets (to match Wordpress.Org tags)
- ? Bootstrap Grid Compatibility Classes
- ? Microthemer Scaffold Declarations
- ? Theme Security/Upgrade Notice Alerts
