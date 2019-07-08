// ==============================================
// === BIOSHIP - CSS HERO DECLARATIONS v1.1.0 ===
// ==============================================

// ---------------------
// FUNCTION DECLARATIONS
// ---------------------

// Main Menu
function csshero_config_main_menu(scope, ulscope, prefix) {
	// if(!ulscope){ulscope='';} if(!prefix) {prefix='Nav';}
	csshero_declare_item(scope,prefix+' Container');
	csshero_declare_item(scope+' ul'+ulscope,prefix+' Menu');
	csshero_declare_item(scope+' ul'+ulscope+' li',prefix+' Menu Item');
	csshero_declare_item(scope+' ul'+ulscope+' li.active',prefix+' Current Menu Item'); // added
	csshero_declare_item(scope+' ul'+ulscope+' li a',prefix+' Menu Link');
	csshero_declare_item(scope+' ul'+ulscope+' li ul.sub-menu',prefix+' SubMenu'); // fixed
	csshero_declare_item(scope+' ul'+ulscope+' li ul li',prefix+' SubMenu Item');
	csshero_declare_item(scope+' ul'+ulscope+' li ul li.active',prefix+' Current SubMenu Item'); // added
	csshero_declare_item(scope+' ul'+ulscope+' li ul li a',prefix+' subMenu Link');
	// csshero_declare_item(scope+' ul'+ulscope+' .sub-menu',prefix+' Submenu');
	// csshero_declare_item(scope+' ul'+ulscope+' li.current-menu-item a',prefix+' Currently Active Menu Link');
	// csshero_declare_item(scope+' ul'+ulscope+' .current-menu-item > a, .main-navigation .current-menu-ancestor > a, .main-navigation .current_page_item > a, .main-navigation .current_page_ancestor > a',prefix+' - Current Menu Ancestor Link');
}

// Main Sidebar
function csshero_config_main_sidebar(scope, inner_scope, prefix) {
	// if(!inner_scope){inner_scope='.widget';} if(!prefix){prefix='Sidebar';}
	// inner_scope=inner_scope+':not(.woocommerce)';
	csshero_declare_item(scope,prefix);
	csshero_declare_item(scope+' '+inner_scope,prefix+' Widget');
	csshero_declare_item(scope+' h3.widget-title',prefix+' Widget Title'); // fixed
	csshero_declare_item(scope+' '+inner_scope+' ul',prefix+' List Container');
	csshero_declare_item(scope+' '+inner_scope+' ul li',prefix+' List Element');
	csshero_declare_item(scope+' '+inner_scope+' a',prefix+' Link');
	csshero_declare_item(scope+' '+inner_scope+' p',prefix+' Paragraph');
	csshero_declare_item(scope+' '+inner_scope+' img',prefix+' Image');
	// csshero_declare_item(scope+' '+inner_scope+' h1',prefix+' Widget h1');
	// csshero_declare_item(scope+' '+inner_scope+' h2',prefix+' Widget h2');
	// csshero_declare_item(scope+' '+inner_scope+' h3:not('+inner_scope+'-title)',prefix+' Widget h3');
	csshero_declare_item(scope+' '+inner_scope+' h4',prefix+' Widget h4');
	csshero_declare_item(scope+' '+inner_scope+' h5',prefix+' Widget h5');
	csshero_declare_item(scope+' '+inner_scope+' h6',prefix+' Widget h6');
	// csshero_declare_item(scope+' '+inner_scope+' #s',prefix+' Search Input');
	// csshero_declare_item(scope+' '+inner_scope+' #searchsubmit',prefix+' Search Submit');
	// csshero_declare_item(scope+' '+inner_scope+' #searchform',prefix+' Searchform');
	// csshero_declare_item(scope+' '+inner_scope+' input[type="submit"]',prefix+' Submit Button');
	// csshero_declare_item(scope+' '+inner_scope+' button',prefix+' Button');
}

// Subsidiary Sidebar
function csshero_config_sub_sidebar(scope, inner_scope, prefix) {
	// if(!inner_scope){inner_scope='.widget';} if(!prefix){prefix='Sidebar';}
	// inner_scope=inner_scope+':not(.woocommerce)';
	csshero_declare_item(scope,prefix);
	csshero_declare_item(scope+' '+inner_scope,prefix+' Widget');
	csshero_declare_item(scope+' .widget-title',prefix+' Widget Title'); // fixed
	csshero_declare_item(scope+' '+inner_scope+' ul',prefix+' List Container');
	csshero_declare_item(scope+' '+inner_scope+' ul li',prefix+' List Element');
	csshero_declare_item(scope+' '+inner_scope+' a',prefix+' Link');
	csshero_declare_item(scope+' '+inner_scope+' p',prefix+' Paragraph');
	csshero_declare_item(scope+' '+inner_scope+' img',prefix+' Image');
}

// Post Type
function csshero_config_post_type(scope, inner_scope, prefix) {

	csshero_declare_item(scope,prefix);
	csshero_declare_item(scope+' .entry-header',prefix+' Header');
	csshero_declare_item(scope+' .entry-header .entry-title',prefix+' Header Title');
	csshero_declare_item(scope+' .entry-header .entry-title a',prefix+' Header Title Link');
	csshero_declare_item(scope+' .entry-header .comments-link',prefix+' Header Comments Area');
	csshero_declare_item(scope+' .entry-header .comments-link a',prefix+' Header Comments Area Link');
	// csshero_declare_item(scope+' .page-title',prefix+' Page Title'); // ???

	csshero_declare_item(scope+' .entry-title',prefix+' Entry Title');
	csshero_declare_item(scope+' .entry-title a',prefix+' Entry Title Link');
	csshero_declare_item(scope+' .entry-subtitle',prefix+' Entry Subtitle'); // added

	csshero_declare_item(scope+' '+inner_scope+' h1',prefix+' Content h1');
	csshero_declare_item(scope+' '+inner_scope+' h2',prefix+' Content h2');
	csshero_declare_item(scope+' '+inner_scope+' h3',prefix+' Content h3');
	csshero_declare_item(scope+' '+inner_scope+' h4',prefix+' Content h4');
	csshero_declare_item(scope+' '+inner_scope+' h5',prefix+' Content h5');
	csshero_declare_item(scope+' '+inner_scope+' h6',prefix+' Content h6');

	// csshero_declare_item(scope+' .entry-header img.wp-post-image',prefix+' Entry Header Images');

	csshero_declare_item(scope+' '+inner_scope,prefix+' Entry Content');
	csshero_declare_item(scope+' '+inner_scope+' p',prefix+' Content Paragraph');
	csshero_declare_item(scope+' '+inner_scope+' a',prefix+' Content Links');
	csshero_declare_item(scope+' '+inner_scope+' blockquote',prefix+' Content Blockquotes');
	csshero_declare_item(scope+' '+inner_scope+' blockquote p',prefix+' Content Blockquotes Paragraph');

	csshero_declare_item(scope+' '+inner_scope+' ul',prefix+' Unordered List');
	csshero_declare_item(scope+' '+inner_scope+' ul li',prefix+' Unordered List Item');
	csshero_declare_item(scope+' '+inner_scope+' ol',prefix+' Ordered List');
	csshero_declare_item(scope+' '+inner_scope+' ol li',prefix+' Ordered List Item');

	// csshero_declare_item(scope+' '+inner_scope+' ins',prefix+' Content Inserted Parts');
	// csshero_declare_item(scope+' '+inner_scope+' del',prefix+' Content Deleted Parts');

	csshero_declare_item(scope+' '+inner_scope+' img:not(.wp-smiley)',prefix+' Content Images');
	csshero_declare_item(scope+' '+inner_scope+' img.wp-smiley',prefix+' Content Smiles');
	csshero_declare_item(scope+' '+inner_scope+' .wp-caption','Caption Area');
	csshero_declare_item(scope+' '+inner_scope+' .wp-caption a','Caption Links');
	csshero_declare_item(scope+' '+inner_scope+' .wp-caption .wp-caption-text','Caption Text');

	csshero_declare_item(scope+' '+inner_scope+' table',prefix+' Table Body');
	csshero_declare_item(scope+' '+inner_scope+' tr',prefix+' Table Row');
	csshero_declare_item(scope+' '+inner_scope+' td',prefix+' Table Cell');

	csshero_declare_item(scope+' .entry-meta',prefix+' Meta Area');
	csshero_declare_item(scope+' .entry-meta a',prefix+' Meta Link');
	// csshero_declare_item(scope+' p.tags',prefix+' Tags Area');
	// csshero_declare_item(scope+' p.tags a',prefix+' Tag');

	/* Entry Footer/Meta */
	csshero_declare_item(scope+' '+inner_scope+' .entry-footer',prefix+' Entry Footer');
	csshero_declare_item(scope+' '+inner_scope+' .entry-footer .entry-utility',prefix+' Meta Bottom');

	/* Post/Page Navigation */
	csshero_declare_item(scope+' '+inner_scope+' #nav-below',prefix+'Navigation Area');
	if (prefix == '[PAGE]') {csshero_declare_item(scope+' '+inner_scope+' #nav-below .page-link','Page Navigation Link');}
	else {
		csshero_declare_item(scope+' '+inner_scope+' #nav-below .nav-prev',prefix+' Navigation Previous');
		csshero_declare_item(scope+' '+inner_scope+' #nav-below .nav-prev',prefix+' Navigation Next');
	}

}

/* Old Config Comments Function
function csshero_config_comments(scope){
	if(!scope){scope='#comments'}
	csshero_declare_item(scope,'Comments Area');
	csshero_declare_item(scope+' .comments-title','Comments Area Title');
	csshero_declare_item(scope+' #comments-title','Comments Area Title');
	csshero_declare_item(scope+' .commentlist li article','Comment');
	csshero_declare_item(scope+' .commentlist li article.comment','Comment');
	csshero_declare_item(scope+' .commentlist li div.comment','Comment');
	csshero_declare_item(scope+' .commentlist .pingback','Comment Pingback');
	csshero_declare_item(scope+' .commentlist .comment .comment-author,'+scope+' .commentlist .fn','Comment Author');
	csshero_declare_item(scope+' .commentlist .comment .comment-author a','Comment Author Link');
	csshero_declare_item(scope+' .commentlist .comment header','Comment Header');
	csshero_declare_item(scope+' .commentlist .comment time','Comment Date');
	csshero_declare_item(scope+' .commentlist .comment .avatar','Comment Author Avatar');
	csshero_declare_item(scope+' .commentlist .comment-content p','Comment Paragraph');
	csshero_declare_item(scope,'Comments Area');
	csshero_declare_item(scope+' .comments-title','Comments Area Title');
	csshero_declare_item(scope+' .comment-list li article','Comment');
	csshero_declare_item(scope+' .comment-list li article.comment','Comment');
	csshero_declare_item(scope+' .comment-list li div.comment','Comment');
	csshero_declare_item(scope+' .comment-list .pingback','Comment Pingback');
	csshero_declare_item(scope+' .comment-list .comment .comment-author, '+scope+' .comment-list .fn, '+scope+' .comment-list .comment .comment-author a','Comment Author');
	csshero_declare_item(scope+' .comment-list .comment header','Comment Header');
	csshero_declare_item(scope+' .comment-list .comment time','Comment Date');
	csshero_declare_item(scope+' .comment-list .comment .avatar','Comment Author Avatar');
	csshero_declare_item(scope+' .comment-list .comment-content p','Comment Paragraph');
} */

// Comments
function new_csshero_config_comment_area(scope, listname, comment_container, prefix) {
	if(!scope){scope='#comments'}
	csshero_declare_item(scope,prefix+' Area');
	csshero_declare_item(scope+' .comments-title',prefix+' Area Title');
	csshero_declare_item(scope+' '+listname+' '+comment_container,prefix+' Single Comment');
	csshero_declare_item(scope+' '+listname+' '+comment_container+'.pingback',prefix+' Comment Pingback');
	csshero_declare_item(scope+' '+listname+' '+comment_container+' .comment-author img',prefix+' Comment Author Avatar'); // mod
	csshero_declare_item(scope+' '+listname+' '+comment_container+' .comment-author-meta',prefix+' Comment Author'); // mod
	csshero_declare_item(scope+' '+listname+' '+comment_container+' .comment-author-meta a',prefix+' Comment Author Link'); // mod
	csshero_declare_item(scope+' '+listname+' '+comment_container+' .comment-time',prefix+' Comment Time'); // mod
	// csshero_declare_item(scope+' '+listname+' '+comment_container+' header',prefix+' Comment Header');
	// csshero_declare_item(scope+' '+listname+' '+comment_container+' time',prefix+' Comment Date');
	csshero_declare_item(scope+' '+listname+' '+comment_container+' .comment-edit-link',prefix+' Comment Edit Link'); // added
	csshero_declare_item(scope+' '+listname+' '+comment_container+' .comment-reply-link',prefix+' Comment Reply Link');
	csshero_declare_item(scope+' '+listname+' '+comment_container+' p',prefix+' Comment Paragraph');
	csshero_declare_item(scope+' '+listname+' '+comment_container+' p a',prefix+' Comment Link');
	csshero_declare_item(scope+' '+listname+' .children',prefix+' Comment Children Area');
	csshero_declare_item(scope+' p.nocomments',prefix+' No Comments Text'); // added
}

// Comment Respond Area
function csshero_config_respond_area(scope, innerscope, prefix) {
	if(!scope){scope='#comments'} if(!innerscope){innerscope='#respond'}
	csshero_declare_item(scope+' '+innerscope,prefix+' Area');
	csshero_declare_item(scope+' '+innerscope+' a',prefix+' Links');
	csshero_declare_item(scope+' '+innerscope+' #cancel-comment-reply-link',prefix+' Cancel Reply Link');
	csshero_declare_item(scope+' '+innerscope+' h3#reply-title',prefix+' Title');
	csshero_declare_item(scope+' '+innerscope+' .logged-in-as',prefix+' Logged In Text');
	csshero_declare_item(scope+' '+innerscope+' .logged-in-as a',prefix+' Logged In Text Link');
	csshero_declare_item(scope+' '+innerscope+' .form-allowed-tags',prefix+' Allowed Comments Tags Area');
	csshero_declare_item(scope+' '+innerscope+' .form-allowed-tags code',prefix+' Allowed Comments Tags');
	csshero_declare_item(scope+' '+innerscope+'  #submit',prefix+' Submit Reply');
	csshero_declare_item(scope+' '+innerscope+' .comment-form-comment label',prefix+' Form Title');
	csshero_declare_item(scope+' '+innerscope+' textarea',prefix+' Form Textarea');
	csshero_declare_item(scope+' '+innerscope+' input',prefix+' Form Input');
	csshero_declare_item(scope+' '+innerscope+' input[type=submit]',prefix+' Form Submit Button');
	// csshero_declare_item(scope+' a.comment-reply-link','Comment Reply Link'); // duplicate?
}

// --------------------------
// --- THEME DECLARATIONS ---
// --------------------------

function csshero_theme_declarations() {

	// BODY ELEMENTS
	// -------------
	csshero_declare_item('body','[Body] Background');
	csshero_declare_item('#wrap.container','[Body] Wrapper');
	csshero_declare_item('#wrap .inner','[Body] Wrapper Inner');

	// HEADER AREA
	// -----------
	csshero_declare_item('#header','[Header] Area');
	csshero_declare_item('#header .inner','[Header] Inner');
	csshero_declare_item('#header #site-logo img','[Header] Logo Image');
	csshero_declare_item('#header h1#site-title-text','[Header] Site Title Text');
	csshero_declare_item('#header h1#site-title-text a','[Header] Site Title Text Link');
	csshero_declare_item('#header #site-description .site-desc','[Header] Site Tagline');
	csshero_declare_item('#header .header-menu','[Header] Menu');
	csshero_declare_item('#header #header-extras','[Header] Extra HTML');
	csshero_config_sub_sidebar('#header #sidebar-header','.widget-container','[Header] Widget Area:');

	// NAVBAR
	// ------
	// csshero_config_menu('scope','ul scope','description');
	csshero_declare_item('#navigation','[Main Navingation]');
	csshero_config_main_menu('#navigation #mainmenu','.menu','[Main Nav]');

	// BANNERS
	// -------
	csshero_declare_item('#topbanner','[Banner] Above Header');
	csshero_declare_item('#headerbanner','[Banner] Below Header');
	csshero_declare_item('#navbarbanner','[Banner] Below Main Menu');
	csshero_declare_item('#footerbanner','[Banner] Above Footer');
	csshero_declare_item('#bottombanner','[Banner] Below Footer');

	// SEARCH
	// ------
	csshero_declare_item('#searchform','[Search] Form');
	csshero_declare_item('#s','[Search] Text Input');
	csshero_declare_item('#searchsubmit','[Search] Submit Button');

	// SIDEBARS
	// --------
	// csshero_config_sidebar('scope','widget scope','description');
	csshero_config_main_sidebar('#sidebar','.widget-container','[Primary Sidebar]');
	csshero_config_main_sidebar('#subsidebar','.widget-container','[Secondary Sidebar]');


	// BUTTONS?
	// --------
	// csshero_declare_item(scope+' '+inner_scope+' input[type="submit"]',prefix+' Submit Button');
	// csshero_declare_item(scope+' '+inner_scope+' button',prefix+' Button');

	// CONTENT AREA
	// ------------
	csshero_declare_item('#content','[Main Content] Area');
	csshero_declare_item('#contentpadding','[Main Content] Inner');

	// POSTS/PAGES
	// -----------
	// csshero_config_post('scope','content scope','description');
	csshero_config_post_type('body.post #content','.entry-content','[POST]');
	csshero_config_post_type('body.page #content','.entry-content','[PAGE]');

	// ARCHIVE PAGES
	// -------------
	// ! TODO !
	// csshero_declare_item('body.archive','.entry-content','Archive Content');
	// csshero_declare_item('body.archive','.entry-summary','Archive Excerpt');

	// COMMENTS
	// --------
	// new_csshero_config_comments('scope','ul\ol scope','single comment scope');
	new_csshero_config_comment_area('#comments','.commentlist','.comment','[Comments]');
	// csshero_config_respond('comments scope','respond scope');
	csshero_config_respond_area('#comments','#respond','[Respond]');

	// FOOTER AREA
	// -----------
	csshero_declare_item('#footer','[Footer] Area');
	csshero_config_sub_sidebar('#footer #sidebar-footer','.widget-container','[Footer] Widgets:');
	// Footer Widget Area 1,2,3,4
	csshero_declare_item('#footer .footer-menu','[Footer] Menu Area');
	csshero_declare_item('#footer #footercredits','[Footer] Credits');

}