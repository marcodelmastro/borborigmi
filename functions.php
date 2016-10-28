<?php

// load_theme_textdomain('thematic', ABSPATH . 'wp-content/themes/thematic/library/languages');

// Unleash the power of Thematic's dynamic classes
// 
 define('THEMATIC_COMPATIBLE_BODY_CLASS', true);
 define('THEMATIC_COMPATIBLE_POST_CLASS', true);

// Unleash the power of Thematic's comment form
 define('THEMATIC_COMPATIBLE_COMMENT_FORM', true);

// Unleash the power of Thematic's feed link functions
 define('THEMATIC_COMPATIBLE_FEEDLINKS', true);

// Unleash the power of Thematic's comment handling
 define('THEMATIC_COMPATIBLE_COMMENT_HANDLING', true);

// This theme uses wp_nav_menu()
//add_theme_support( 'menus' ); // not neded since version 1

//remove the access menu from its current position
function remove_access() {
  remove_action('thematic_header','thematic_access',9);
}
add_action('init', 'remove_access');

// .. And put it back into action at the top
add_action('thematic_header','thematic_access',0);

// add a home link to the top menu // not needed id WP 3.0 menu are used (add custom object)
//function childtheme_menu_args($args) {
//    $args = array(
//        'show_home' => 'Home',
//        'sort_column' => 'menu_order',
//        'menu_class' => 'menu',
//        'echo' => false
//   );
//   return $args;
//}
//add_filter('wp_page_menu_args','childtheme_menu_args',20);

// register menu for in-page navigation
function register_menus() 
{
    register_nav_menus(
        array('inpage_navigation' => __( 'In-Page Navigation' ))
    );
} 
add_action( 'init', 'register_menus' );

// info after post title 
function my_postheader_postmeta() {
    global $id, $post, $authordata;
    $postmeta = '<div class="entry-meta">';
    $postmeta .= '<span class="entry-date"><abbr class="published" title="';
    $postmeta .= get_the_time(thematic_time_title()) . '">';
    $postmeta .= get_the_time(thematic_time_display());
    $postmeta .= '</abbr></span>';
    // Display edit link
    if (current_user_can('edit_posts')) {
        $postmeta .= '<span class="edit">' . $posteditlink . '</span>';
    }
    $postmeta .= "</div><!-- .entry-meta -->\n";
    return $postmeta;
}
add_filter('thematic_postheader_postmeta','my_postheader_postmeta');

// site info
function childtheme_override_siteinfo() {
  echo '<span class="colophon"><a href=#header title="Torna in cima" rel="nofollow">Torna in cima</a></span>' ;
}


// only excerpt on archive pages
function child_content($content) {
  if ( is_category() ) {
    $content = 'excerpt';
  }
  return $content;
}
add_filter('thematic_content', 'child_content');

// page title according to page
function childtheme_page_title() {
  if (is_category()) {
    $content .= '<div class="archive-head">'; /*Begin encompassing div*/
    $content .= '<h1 class="page-title">';
    $content .= __('', 'thematic'); /*This removes the "Category Archives:" text*/
    $content .= ' <span>';
    $content .= single_cat_title('', FALSE);
    $content .= '</span></h1>';/*End Category Archive Title*/
    $content .= '<div class="archive-meta">';/*Begin Category Description*/
  if ( !(''== category_description()) ) : $content .= apply_filters('archive_meta', category_description()); endif;
  $content .= '</div></div>' . "\n"; /*End description and close encompassing div*/
  } elseif (is_tag()) {
    $content .= '<div class="archive-head">';/*Same deal*/
    $content .= '<h1 class="page-title">';
    $content .= __('', 'thematic');
    $content .= ' <span>';
    $content .= single_tag_title('', FALSE);
    $content .= '</span></h1>';
    $content .= '<div class="archive-meta">';
  if ( !(''== tag_description()) ) : $content .= apply_filters('archive_meta', tag_description()); endif;
  $content .= '</div></div>' . "\n";
  } elseif (is_tax())	{
    global $taxonomy;
    $content .= '<div class="archive-head">';
    $content .= '<h1 class="page-title">';
    $tax = get_taxonomy($taxonomy);
    //$content .= $tax->labels->name . ' ';/*Hides the actual taxonomy name*/
    $content .= __(' ', 'thematic');
    $content .= '<span>';
    $content .= thematic_get_term_name();/*Similar to single_cat_title*/
    $content .= '</span></h1>';
    $content .= '<div class="archive-meta">';
    if ( !(''== term_description()) ) : $content .= apply_filters('archive_meta', term_description()); endif;
    $content .= '</div></div>' . "\n";
  }
  return $content;
}
add_filter('thematic_page_title', 'childtheme_page_title');

// favicon
function childtheme_favicon() { ?>
    <link rel="shortcut icon" href="<?php echo bloginfo('stylesheet_directory') ?>/images/favicon.ico">
<?php }
add_action('wp_head', 'childtheme_favicon');

?>