<?php
/**
 * The template to display single post
 *
 * @package CARZ
 * @since CARZ 1.0
 */

// Full post loading
$full_post_loading          = carz_get_value_gp( 'action' ) == 'full_post_loading';

// Prev post loading
$prev_post_loading          = carz_get_value_gp( 'action' ) == 'prev_post_loading';
$prev_post_loading_type     = carz_get_theme_option( 'posts_navigation_scroll_which_block', 'article' );

// Position of the related posts
$carz_related_position   = carz_get_theme_option( 'related_position', 'below_content' );

// Type of the prev/next post navigation
$carz_posts_navigation   = carz_get_theme_option( 'posts_navigation' );
$carz_prev_post          = false;
$carz_prev_post_same_cat = (int)carz_get_theme_option( 'posts_navigation_scroll_same_cat', 1 );

// Rewrite style of the single post if current post loading via AJAX and featured image and title is not in the content
if ( ( $full_post_loading 
		|| 
		( $prev_post_loading && 'article' == $prev_post_loading_type )
	) 
	&& 
	! in_array( carz_get_theme_option( 'single_style' ), array( 'style-6' ) )
) {
	carz_storage_set_array( 'options_meta', 'single_style', 'style-6' );
}

do_action( 'carz_action_prev_post_loading', $prev_post_loading, $prev_post_loading_type );

get_header();

while ( have_posts() ) {

	the_post();

	// Type of the prev/next post navigation
	if ( 'scroll' == $carz_posts_navigation ) {
		$carz_prev_post = get_previous_post( $carz_prev_post_same_cat );  // Get post from same category
		if ( ! $carz_prev_post && $carz_prev_post_same_cat ) {
			$carz_prev_post = get_previous_post( false );                    // Get post from any category
		}
		if ( ! $carz_prev_post ) {
			$carz_posts_navigation = 'links';
		}
	}

	// Override some theme options to display featured image, title and post meta in the dynamic loaded posts
	if ( $full_post_loading || ( $prev_post_loading && $carz_prev_post ) ) {
		carz_sc_layouts_showed( 'featured', false );
		carz_sc_layouts_showed( 'title', false );
		carz_sc_layouts_showed( 'postmeta', false );
	}

	// If related posts should be inside the content
	if ( strpos( $carz_related_position, 'inside' ) === 0 ) {
		ob_start();
	}

	// Display post's content
	get_template_part( apply_filters( 'carz_filter_get_template_part', 'templates/content', 'single-' . carz_get_theme_option( 'single_style' ) ), 'single-' . carz_get_theme_option( 'single_style' ) );

	// If related posts should be inside the content
	if ( strpos( $carz_related_position, 'inside' ) === 0 ) {
		$carz_content = ob_get_contents();
		ob_end_clean();

		ob_start();
		do_action( 'carz_action_related_posts' );
		$carz_related_content = ob_get_contents();
		ob_end_clean();

		if ( ! empty( $carz_related_content ) ) {
			$carz_related_position_inside = max( 0, min( 9, carz_get_theme_option( 'related_position_inside' ) ) );
			if ( 0 == $carz_related_position_inside ) {
				$carz_related_position_inside = mt_rand( 1, 9 );
			}

			$carz_p_number         = 0;
			$carz_related_inserted = false;
			$carz_in_block         = false;
			$carz_content_start    = strpos( $carz_content, '<div class="post_content' );
			$carz_content_end      = strrpos( $carz_content, '</div>' );

			for ( $i = max( 0, $carz_content_start ); $i < min( strlen( $carz_content ) - 3, $carz_content_end ); $i++ ) {
				if ( $carz_content[ $i ] != '<' ) {
					continue;
				}
				if ( $carz_in_block ) {
					if ( strtolower( substr( $carz_content, $i + 1, 12 ) ) == '/blockquote>' ) {
						$carz_in_block = false;
						$i += 12;
					}
					continue;
				} else if ( strtolower( substr( $carz_content, $i + 1, 10 ) ) == 'blockquote' && in_array( $carz_content[ $i + 11 ], array( '>', ' ' ) ) ) {
					$carz_in_block = true;
					$i += 11;
					continue;
				} else if ( 'p' == $carz_content[ $i + 1 ] && in_array( $carz_content[ $i + 2 ], array( '>', ' ' ) ) ) {
					$carz_p_number++;
					if ( $carz_related_position_inside == $carz_p_number ) {
						$carz_related_inserted = true;
						$carz_content = ( $i > 0 ? substr( $carz_content, 0, $i ) : '' )
											. $carz_related_content
											. substr( $carz_content, $i );
					}
				}
			}
			if ( ! $carz_related_inserted ) {
				if ( $carz_content_end > 0 ) {
					$carz_content = substr( $carz_content, 0, $carz_content_end ) . $carz_related_content . substr( $carz_content, $carz_content_end );
				} else {
					$carz_content .= $carz_related_content;
				}
			}
		}

		carz_show_layout( $carz_content );
	}

	// Comments
	do_action( 'carz_action_before_comments' );
	comments_template();
	do_action( 'carz_action_after_comments' );

	// Related posts
	if ( 'below_content' == $carz_related_position
		&& ( 'scroll' != $carz_posts_navigation || (int)carz_get_theme_option( 'posts_navigation_scroll_hide_related', 0 ) == 0 )
		&& ( ! $full_post_loading || (int)carz_get_theme_option( 'open_full_post_hide_related', 1 ) == 0 )
	) {
		do_action( 'carz_action_related_posts' );
	}

	// Post navigation: type 'scroll'
	if ( 'scroll' == $carz_posts_navigation && ! $full_post_loading ) {
		?>
		<div class="nav-links-single-scroll"
			data-post-id="<?php echo esc_attr( get_the_ID( $carz_prev_post ) ); ?>"
			data-post-link="<?php echo esc_attr( get_permalink( $carz_prev_post ) ); ?>"
			data-post-title="<?php the_title_attribute( array( 'post' => $carz_prev_post ) ); ?>"
			data-cur-post-link="<?php echo esc_attr( get_permalink() ); ?>"
			data-cur-post-title="<?php the_title_attribute(); ?>"
			<?php do_action( 'carz_action_nav_links_single_scroll_data', $carz_prev_post ); ?>
		></div>
		<?php
	}
}

get_footer();
