<?php
/**
 * The Classic template to display the content
 *
 * Used for index/archive/search.
 *
 * @package CARZ
 * @since CARZ 1.0
 */

$carz_template_args = get_query_var( 'carz_template_args' );

if ( is_array( $carz_template_args ) ) {
	$carz_columns    = empty( $carz_template_args['columns'] ) ? 2 : max( 1, $carz_template_args['columns'] );
	$carz_blog_style = array( $carz_template_args['type'], $carz_columns );
    $carz_columns_class = carz_get_column_class( 1, $carz_columns, ! empty( $carz_template_args['columns_tablet']) ? $carz_template_args['columns_tablet'] : '', ! empty($carz_template_args['columns_mobile']) ? $carz_template_args['columns_mobile'] : '' );
} else {
	$carz_template_args = array();
	$carz_blog_style = explode( '_', carz_get_theme_option( 'blog_style' ) );
	$carz_columns    = empty( $carz_blog_style[1] ) ? 2 : max( 1, $carz_blog_style[1] );
    $carz_columns_class = carz_get_column_class( 1, $carz_columns );
}
$carz_expanded   = ! carz_sidebar_present() && carz_get_theme_option( 'expand_content' ) == 'expand';

$carz_post_format = get_post_format();
$carz_post_format = empty( $carz_post_format ) ? 'standard' : str_replace( 'post-format-', '', $carz_post_format );

?><div class="<?php
	if ( ! empty( $carz_template_args['slider'] ) ) {
		echo ' slider-slide swiper-slide';
	} else {
		echo ( carz_is_blog_style_use_masonry( $carz_blog_style[0] ) ? 'masonry_item masonry_item-1_' . esc_attr( $carz_columns ) : esc_attr( $carz_columns_class ) );
	}
?>"><article id="post-<?php the_ID(); ?>" data-post-id="<?php the_ID(); ?>"
	<?php
	post_class(
		'post_item post_item_container post_format_' . esc_attr( $carz_post_format )
				. ' post_layout_classic post_layout_classic_' . esc_attr( $carz_columns )
				. ' post_layout_' . esc_attr( $carz_blog_style[0] )
				. ' post_layout_' . esc_attr( $carz_blog_style[0] ) . '_' . esc_attr( $carz_columns )
	);
	carz_add_blog_animation( $carz_template_args );
	?>
>
	<?php

	// Sticky label
	if ( is_sticky() && ! is_paged() ) {
		?>
		<span class="post_label label_sticky"></span>
		<?php
	}

	// Featured image
	$carz_hover      = ! empty( $carz_template_args['hover'] ) && ! carz_is_inherit( $carz_template_args['hover'] )
							? $carz_template_args['hover']
							: carz_get_theme_option( 'image_hover' );

	$carz_components = ! empty( $carz_template_args['meta_parts'] )
							? ( is_array( $carz_template_args['meta_parts'] )
								? $carz_template_args['meta_parts']
								: explode( ',', $carz_template_args['meta_parts'] )
								)
							: carz_array_get_keys_by_value( carz_get_theme_option( 'meta_parts' ) );

	carz_show_post_featured( apply_filters( 'carz_filter_args_featured',
		array(
			'thumb_size' => ! empty( $carz_template_args['thumb_size'] )
				? $carz_template_args['thumb_size']
				: carz_get_thumb_size(
					'classic' == $carz_blog_style[0]
						? ( strpos( carz_get_theme_option( 'body_style' ), 'full' ) !== false
								? ( $carz_columns > 2 ? 'big' : 'huge' )
								: ( $carz_columns > 2
									? ( $carz_expanded ? 'square' : 'square' )
									: ($carz_columns > 1 ? 'square' : ( $carz_expanded ? 'huge' : 'big' ))
									)
							)
						: ( strpos( carz_get_theme_option( 'body_style' ), 'full' ) !== false
								? ( $carz_columns > 2 ? 'masonry-big' : 'full' )
								: ($carz_columns === 1 ? ( $carz_expanded ? 'huge' : 'big' ) : ( $carz_columns <= 2 && $carz_expanded ? 'masonry-big' : 'masonry' ))
							)
			),
			'hover'      => $carz_hover,
			'meta_parts' => $carz_components,
			'no_links'   => ! empty( $carz_template_args['no_links'] ),
        ),
        'content-classic',
        $carz_template_args
    ) );

	// Title and post meta
	$carz_show_title = get_the_title() != '';
	$carz_show_meta  = count( $carz_components ) > 0 && ! in_array( $carz_hover, array( 'border', 'pull', 'slide', 'fade', 'info' ) );

	if ( $carz_show_title ) {
		?>
		<div class="post_header entry-header">
			<?php

			// Post meta
			if ( apply_filters( 'carz_filter_show_blog_meta', $carz_show_meta, $carz_components, 'classic' ) ) {
				if ( count( $carz_components ) > 0 ) {
					do_action( 'carz_action_before_post_meta' );
					carz_show_post_meta(
						apply_filters(
							'carz_filter_post_meta_args', array(
							'components' => join( ',', $carz_components ),
							'seo'        => false,
							'echo'       => true,
						), $carz_blog_style[0], $carz_columns
						)
					);
					do_action( 'carz_action_after_post_meta' );
				}
			}

			// Post title
			if ( apply_filters( 'carz_filter_show_blog_title', true, 'classic' ) ) {
				do_action( 'carz_action_before_post_title' );
				if ( empty( $carz_template_args['no_links'] ) ) {
					the_title( sprintf( '<h4 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h4>' );
				} else {
					the_title( '<h4 class="post_title entry-title">', '</h4>' );
				}
				do_action( 'carz_action_after_post_title' );
			}

			if( !in_array( $carz_post_format, array( 'quote', 'aside', 'link', 'status' ) ) ) {
				// More button
				if ( apply_filters( 'carz_filter_show_blog_readmore', ! $carz_show_title || ! empty( $carz_template_args['more_button'] ), 'classic' ) ) {
					if ( empty( $carz_template_args['no_links'] ) ) {
						do_action( 'carz_action_before_post_readmore' );
						carz_show_post_more_link( $carz_template_args, '<div class="more-wrap">', '</div>' );
						do_action( 'carz_action_after_post_readmore' );
					}
				}
			}
			?>
		</div><!-- .entry-header -->
		<?php
	}

	// Post content
	if( in_array( $carz_post_format, array( 'quote', 'aside', 'link', 'status' ) ) ) {
		ob_start();
		if (apply_filters('carz_filter_show_blog_excerpt', empty($carz_template_args['hide_excerpt']) && carz_get_theme_option('excerpt_length') > 0, 'classic')) {
			carz_show_post_content($carz_template_args, '<div class="post_content_inner">', '</div>');
		}
		// More button
		if(! empty( $carz_template_args['more_button'] )) {
			if ( empty( $carz_template_args['no_links'] ) ) {
				do_action( 'carz_action_before_post_readmore' );
				carz_show_post_more_link( $carz_template_args, '<div class="more-wrap">', '</div>' );
				do_action( 'carz_action_after_post_readmore' );
			}
		}
		$carz_content = ob_get_contents();
		ob_end_clean();
		carz_show_layout($carz_content, '<div class="post_content entry-content">', '</div><!-- .entry-content -->');
	}
	?>

</article></div><?php
// Need opening PHP-tag above, because <div> is a inline-block element (used as column)!
