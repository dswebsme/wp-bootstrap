<?php
/**
 * WP Bootstrap
 * Version: 0.3.1
 * 
 * Author: D.S. Webster - @dswebsme
 * Website: http://dswebs.me/
 * License: GPL v2.0
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * 
 * WP Bootstrap uses action hooks, filters and callbacks to modify the HTML 
 * output of several core WordPress functions to achieve Bootstrap compatible
 * markup with minimal impact to your code, content or raw MySQL data.
 * 
 * This file contains open-source code written by other contributors. Their
 * respective credits, licenses and code contributions are denoted in the
 * following format:
 * 
 * 		// BEGIN %CONTRIBUTION_NAME% (%AUTHOR_NAME%)
 * 
 * 		%source_code%
 * 
 * 		// END %CONTRIBUTION_NAME% (%AUTHOR_NAME%)
 */

// NAV
// add bootstrap markup to menus via a custom walker (wp_bootstrap_navwalker)
function wpbs_nav_menu_args_filter($args)
{
	if(empty($args['walker']))
	{
		$args['fallback_cb'] = 'wp_bootstrap_navwalker::fallback';
		$args['walker'] = new wp_bootstrap_navwalker();
	}
	
	return $args;
}
add_filter('wp_nav_menu_args', 'wpbs_nav_menu_args_filter');


// CONTENT
// add bootstrap alignment classes to images
function wpbs_content_image_alignmment_filter($content)
{
	$search = array('alignleft', 'alignright', 'aligncenter');
	$replace = array('alignleft pull-left', 'alignright pull-right', 'aligncenter center-block');
	return $content = str_replace($search, $replace, $content);
}
add_filter('the_content', 'wpbs_content_image_alignmment_filter', 11);

// add bootstrap markup to content elements
function wpbs_content_responsive_image_filter($content)
{	
	$search = array('<img class="');
	$replace = array('<img class="img-responsive ');
	return $content = str_replace($search, $replace, $content);
}
add_filter('the_content', 'wpbs_content_responsive_image_filter', 11);

// add bootstrap markup to content elements
function wpbs_content_table_classes_filter($content)
{
	$search = array('<table>');
	$replace = array('<table class="table table-striped table-responsive">');
	return $content = str_replace($search, $replace, $content);
}
add_filter('the_content', 'wpbs_content_table_classes_filter', 11);

// add bootstrap markup to the image caption shortcode
function wpbs_img_caption_shortcode_filter($value, $attr, $content)
{
	// see 'wp-includes/media.php' in WP core to learn more about the 'img_caption_shortcode' filter hook
	$atts = shortcode_atts( array(
		'id'	  => '',
		'align'	  => 'alignnone',
		'width'	  => '',
		'caption' => ''
	), $attr, 'caption' );

	$atts['width'] = (int) $atts['width'];
	if ( $atts['width'] < 1 || empty( $atts['caption'] ) )
		return $content;

	if ( ! empty( $atts['id'] ) )
		$atts['id'] = 'id="' . esc_attr( $atts['id'] ) . '" ';

	$caption_width = 6 + $atts['width'];

	// see 'wp-includes/media.php' in WP core to learn more about the 'img_caption_shortcode_width' filter hook
	$caption_width = apply_filters( 'img_caption_shortcode_width', $caption_width, $atts, $content );

	$style = '';
	if ( $caption_width )
		$style = 'style="width: ' . (int) $caption_width . 'px" ';
	
	$html = '<div ' . $atts['id'] . $style . 'class="wp-caption img-responsive thumbnail ' . esc_attr( $atts['align'] ) . '">';
	$html.= do_shortcode($content);
	$html.= '<div class="wp-caption-text caption">' . $atts['caption'] . '</div>';
	$html.= '</div>';
	
	return $html;
}
add_filter('img_caption_shortcode', 'wpbs_img_caption_shortcode_filter', 10, 3);


// COMMENTS
// add bootstrap classes to avatars
function wpbs_get_avatar_filter($avatar, $id_or_email, $size, $default, $alt)
{
	// add the media-object class to the avatar
	$search = array("class='");
	$replace = array("class='media-object img-circle ");
	return $avatar = str_replace($search, $replace, $avatar);
}
add_filter('get_avatar', 'wpbs_get_avatar_filter', 10, 5);

// add a custom callback to "wp_list_comments" args to enable Bootstrap style comments
function wpbs_list_comments_args_fitler($r)
{
	if(empty($r['callback']))
	{
		$r['callback'] = 'wpbs_comment_callback';
		$r['style'] = 'ol';
	}
	
	return $r;
}
add_filter('wp_list_comments_args', 'wpbs_list_comments_args_fitler');

// render Bootstrap style comments via custom callback
function wpbs_comment_callback($comment, $args, $depth)
{
	?>
	
	<li id="comment-<?php comment_ID(); ?>" <?php comment_class(array(empty($args['has_children']) ? '' : 'parent', 'media comment')); ?>>
		
		<?php if($args['avatar_size'] != 0): ?>
			<div class="comment-avatar pull-left">
				<?php echo get_avatar($comment, $args['avatar_size']); ?>
			</div>
		<?php endif; ?>
		
		<ul class="comment-meta commentmetadata list-unstyled list-inline">
			<li class="comment-author">
				<h4 class="comment-heading media-heading">
					<?php printf( __( '<cite class="fn">%s</cite> <span class="says"></span>' ), get_comment_author_link() ); ?>
				</h4>
			</li>
			<li class="comment-time text-muted pull-right">
				<small>
					<span class="glyphicon glyphicon-time"></span>
					<a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ); ?>" title="Permalink">
						<?php printf( __('%1$s &bull; %2$s'), get_comment_date(),  get_comment_time() ); ?>
					</a>
				</small>
			</li>
		</ul>
		
		<div id="div-comment-<?php comment_ID(); ?>" class="comment-body media-body well well-sm">
			<?php comment_text(); ?>
		</div>
		
		<div id="div-comment-<?php comment_ID(); ?>-functions" class="comment-functions">
			<ul class="list-unstyled list-inline text-right">
				
				<?php if($comment->comment_approved == '0'): ?>
					<li class="comment-awaiting-moderation text-info">
						<small class="glyphicon glyphicon-info-sign"></small>
						<?php _e( 'Your comment is awaiting moderation.' ); ?>
					</li>
				<?php endif; ?>
				
				<li class="comment-edit"><?php edit_comment_link(__( 'Edit' )); ?></li>
			
				<li class="comment-reply"><?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?></li>
			</ul>
		</div>
	
	<?php
	// the closing tag is handled by WordPress via the end_callback method
}

// add bootstrap classes to the comment reply link
function wpbs_comment_reply_link_filter($link, $args, $comment, $post)
{
	// give the reply link bootstrap button formatting
	$search = array('comment-reply-');
	$replace = array('btn btn-default btn-xs comment-reply-');
	return $link = str_replace($search, $replace, $link);
}
add_filter('comment_reply_link', 'wpbs_comment_reply_link_filter', 10, 4);

// add bootstrap classes to the comment reply link
function wpbs_edit_comment_link_filter($link, $comment_ID, $text)
{
	// give the reply link bootstrap button formatting
	$search = array('comment-edit-');
	$replace = array('btn btn-default btn-xs comment-edit-');
	return $link = str_replace($search, $replace, $link);
}
add_filter('edit_comment_link', 'wpbs_edit_comment_link_filter', 10, 3);

// add bootstrap markup to the comment form
function wpbs_comment_form_defaults_filter($defaults)
{
	// force html5 format
	$defaults['format'] = 'html5';
	
	// pull the 'require_name_email' option for later use
	$required = get_option('require_name_email');
	
	// boostrap markup to denote required fields
	$aria_required = ( $required ? " aria-required='true'" : '' );
	$required_markup = '<sup class="required glyphicon glyphicon-asterisk text-danger"></sup>';
	$required_text = sprintf( ' ' . __('Required fields are marked %s'), $required_markup );
	
	// bootstrap style required text
	$defaults['comment_notes_before'] = '<p class="comment-notes">' . __('Your email address will not be published.') . ($required ? $required_text : '') . '</p>';
	
	// bootstrap markup for the author label and input
	$author = '
		<div class="comment-form-author form-group">
			<label for="author" class="control-label col-sm-2">' . __('Name') . ' ' . ($required ? $required_markup : '') . '</label>
			<div class="col-sm-10">
				<input type="text" id="author" name="author" class="form-control" placeholder="John Smith" ' . $aria_required . ' />
			</div>
		</div>
	';
	
	// bootstrap markup for the email label and input
	$email = '
		<div class="comment-form-email form-group">
			<label for="email" class="control-label col-sm-2">' . __('Email') . ' ' . ($required ? $required_markup : '') . '</label>
			<div class="col-sm-10">
				<input type="email" id="email" name="email" class="form-control" placeholder="example@domain.com" ' . $aria_required . ' />
			</div>
		</div>
	';
	
	// bootstrap markup for the url label and input
	$url = '
		<div class="comment-form-url form-group">
			<label for="url" class="control-label col-sm-2">Website</label>
			<div class="col-sm-10">
				<input type="url" id="url" name="url" class="form-control" placeholder="http://www.example.com" />
			</div>
		</div>
	';
	
	// bootstrap markup for the comment label and textarea
	$comment = '
		<div class="comment-form-comment form-group">
			<label for="comment" class="control-label col-sm-2">Comment</label>
			<div class="col-sm-10">
				<textarea id="comment" name="comment" class="form-control" rows="5" aria-required="true"></textarea>
			</div>
		</div>
	';
	
	// bootstrap markup for the allowed tags notes
	$notes = '
		<div class="comment-form-comment form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<p class="form-allowed-tags">' . sprintf(__('You may use these <abbr title="HyperText Markup Language">HTML</abbr> tags and attributes: %s'), wpbs_allowed_tags()) . '</p>
			</div>
		</div>
	';
	
	// overwrite the default markup for the comment form fields and notes
	$defaults['fields']['author'] = $author;
	$defaults['fields']['email'] = $email;
	$defaults['fields']['url'] = $url;
	$defaults['comment_field'] = $comment;
	$defaults['comment_notes_after'] = $notes;
	
	return $defaults;
}
add_filter('comment_form_defaults', 'wpbs_comment_form_defaults_filter');

// modify the 'allowedtags' function to output bootstrap friendly markup
function wpbs_allowed_tags() {
	global $allowedtags;
	$allowed = '';
	foreach ( (array) $allowedtags as $tag => $attributes ) {
		$allowed .= '<code>&lt;'.$tag;
		if ( 0 < count($attributes) ) {
			foreach ( $attributes as $attribute => $limits ) {
				$allowed .= ' '.$attribute.'=""';
			}
		}
		$allowed .= '&gt;</code> ';
	}
	
	return $allowed;
}

// use jQuery to add bootstrap classes to the comment form on the 'comment_form_after' action
function wpbs_comment_form_after_action()
{
	?>
	
	<script type="text/javascript" charset="utf-8">
		jQuery('.comment-form').addClass('form-horizontal clearfix');
		jQuery('.form-submit input').addClass('btn btn-primary pull-right')
	</script>
	
	<?php
}
add_action( 'comment_form_after', 'wpbs_comment_form_after_action');


// BEGIN wp_bootstrap_navwalker (Edward McIntyre)

/**
 * Class Name: wp_bootstrap_navwalker
 * GitHub URI: https://github.com/twittem/wp-bootstrap-navwalker
 * Description: A custom WordPress nav walker class to implement the Bootstrap 3 navigation style in a custom theme using the WordPress built in menu manager.
 * Version: 2.0.4
 * Author: Edward McIntyre - @twittem
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

class wp_bootstrap_navwalker extends Walker_Nav_Menu {

	/**
	 * @see Walker::start_lvl()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int $depth Depth of page. Used for padding.
	 */
	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat( "\t", $depth );
		$output .= "\n$indent<ul role=\"menu\" class=\" dropdown-menu\">\n";
	}

	/**
	 * @see Walker::start_el()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item Menu item data object.
	 * @param int $depth Depth of menu item. Used for padding.
	 * @param int $current_page Menu item ID.
	 * @param object $args
	 */
	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		/**
		 * Dividers, Headers or Disabled
		 * =============================
		 * Determine whether the item is a Divider, Header, Disabled or regular
		 * menu item. To prevent errors we use the strcasecmp() function to so a
		 * comparison that is not case sensitive. The strcasecmp() function returns
		 * a 0 if the strings are equal.
		 */
		if ( strcasecmp( $item->attr_title, 'divider' ) == 0 && $depth === 1 ) {
			$output .= $indent . '<li role="presentation" class="divider">';
		} else if ( strcasecmp( $item->title, 'divider') == 0 && $depth === 1 ) {
			$output .= $indent . '<li role="presentation" class="divider">';
		} else if ( strcasecmp( $item->attr_title, 'dropdown-header') == 0 && $depth === 1 ) {
			$output .= $indent . '<li role="presentation" class="dropdown-header">' . esc_attr( $item->title );
		} else if ( strcasecmp($item->attr_title, 'disabled' ) == 0 ) {
			$output .= $indent . '<li role="presentation" class="disabled"><a href="#">' . esc_attr( $item->title ) . '</a>';
		} else {

			$class_names = $value = '';

			$classes = empty( $item->classes ) ? array() : (array) $item->classes;
			$classes[] = 'menu-item-' . $item->ID;

			$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );

			if ( $args->has_children )
				$class_names .= ' dropdown';

			if ( in_array( 'current-menu-item', $classes ) )
				$class_names .= ' active';

			$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

			$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
			$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

			$output .= $indent . '<li' . $id . $value . $class_names .'>';

			$atts = array();
			$atts['title']  = ! empty( $item->title )	? $item->title	: '';
			$atts['target'] = ! empty( $item->target )	? $item->target	: '';
			$atts['rel']    = ! empty( $item->xfn )		? $item->xfn	: '';

			// If item has_children add atts to a.
			if ( $args->has_children && $depth === 0 ) {
				$atts['href']   		= '#';
				$atts['data-toggle']	= 'dropdown';
				$atts['class']			= 'dropdown-toggle';
				$atts['aria-haspopup']	= 'true';
			} else {
				$atts['href'] = ! empty( $item->url ) ? $item->url : '';
			}

			$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args );

			$attributes = '';
			foreach ( $atts as $attr => $value ) {
				if ( ! empty( $value ) ) {
					$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
					$attributes .= ' ' . $attr . '="' . $value . '"';
				}
			}

			$item_output = $args->before;

			/*
			 * Glyphicons
			 * ===========
			 * Since the the menu item is NOT a Divider or Header we check the see
			 * if there is a value in the attr_title property. If the attr_title
			 * property is NOT null we apply it as the class name for the glyphicon.
			 */
			if ( ! empty( $item->attr_title ) )
				$item_output .= '<a'. $attributes .'><span class="glyphicon ' . esc_attr( $item->attr_title ) . '"></span>&nbsp;';
			else
				$item_output .= '<a'. $attributes .'>';

			$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
			$item_output .= ( $args->has_children && 0 === $depth ) ? ' <span class="caret"></span></a>' : '</a>';
			$item_output .= $args->after;

			$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
		}
	}

	/**
	 * Traverse elements to create list from elements.
	 *
	 * Display one element if the element doesn't have any children otherwise,
	 * display the element and its children. Will only traverse up to the max
	 * depth and no ignore elements under that depth.
	 *
	 * This method shouldn't be called directly, use the walk() method instead.
	 *
	 * @see Walker::start_el()
	 * @since 2.5.0
	 *
	 * @param object $element Data object
	 * @param array $children_elements List of elements to continue traversing.
	 * @param int $max_depth Max depth to traverse.
	 * @param int $depth Depth of current element.
	 * @param array $args
	 * @param string $output Passed by reference. Used to append additional content.
	 * @return null Null on failure with no changes to parameters.
	 */
	public function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {
        if ( ! $element )
            return;

        $id_field = $this->db_fields['id'];

        // Display this element.
        if ( is_object( $args[0] ) )
           $args[0]->has_children = ! empty( $children_elements[ $element->$id_field ] );

        parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
    }

	/**
	 * Menu Fallback
	 * =============
	 * If this function is assigned to the wp_nav_menu's fallback_cb variable
	 * and a manu has not been assigned to the theme location in the WordPress
	 * menu manager the function with display nothing to a non-logged in user,
	 * and will add a link to the WordPress menu manager if logged in as an admin.
	 *
	 * @param array $args passed from the wp_nav_menu function.
	 *
	 */
	public static function fallback( $args ) {
		if ( current_user_can( 'manage_options' ) ) {

			extract( $args );

			$fb_output = null;

			if ( $container ) {
				$fb_output = '<' . $container;

				if ( $container_id )
					$fb_output .= ' id="' . $container_id . '"';

				if ( $container_class )
					$fb_output .= ' class="' . $container_class . '"';

				$fb_output .= '>';
			}

			$fb_output .= '<ul';

			if ( $menu_id )
				$fb_output .= ' id="' . $menu_id . '"';

			if ( $menu_class )
				$fb_output .= ' class="' . $menu_class . '"';

			$fb_output .= '>';
			$fb_output .= '<li><a href="' . admin_url( 'nav-menus.php' ) . '">Add a menu</a></li>';
			$fb_output .= '</ul>';

			if ( $container )
				$fb_output .= '</' . $container . '>';

			echo $fb_output;
		}
	}
}

// END wp_bootstrap_navwalker (Edward McIntyre)
