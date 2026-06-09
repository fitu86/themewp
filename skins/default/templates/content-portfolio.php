<?php
/**
 * The Portfolio template to display the content
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

$carz_post_format = get_post_format();
$carz_post_format = empty( $carz_post_format ) ? 'standard' : str_replace( 'post-format-', '', $carz_post_format );

?><div class="
<?php
if ( ! empty( $carz_template_args['slider'] ) ) {
	echo ' slider-slide swiper-slide';
} else {
	echo ( carz_is_blog_style_use_masonry( $carz_blog_style[0] ) ? 'masonry_item masonry_item-1_' . esc_attr( $carz_columns ) : esc_attr( $carz_columns_class ));
}
?>
"><article id="post-<?php the_ID(); ?>" 
	<?php
	post_class(
		'post_item post_item_container post_format_' . esc_attr( $carz_post_format )
		. ' post_layout_portfolio'
		. ' post_layout_portfolio_' . esc_attr( $carz_columns )
		. ( 'portfolio' != $carz_blog_style[0] ? ' ' . esc_attr( $carz_blog_style[0] )  . '_' . esc_attr( $carz_columns ) : '' )
	);
	carz_add_blog_animation( $carz_template_args );
	?>
>
<?php

	// Sticky label
	if ( is_sticky() && ! is_paged() ) {
		?><span class="post_label label_sticky"></span><?php
	}

	$carz_hover   = ! empty( $carz_template_args['hover'] ) && ! carz_is_inherit( $carz_template_args['hover'] )
								? $carz_template_args['hover']
								: carz_get_theme_option( 'image_hover' );

	if ( 'dots' == $carz_hover ) {
		$carz_post_link = empty( $carz_template_args['no_links'] )
								? ( ! empty( $carz_template_args['link'] )
									? $carz_template_args['link']
									: get_permalink()
									)
								: '';
		$carz_target    = ! empty( $carz_post_link ) && carz_is_external_url( $carz_post_link ) && function_exists( 'carz_external_links_target' )
								? carz_external_links_target()
								: '';
	}
	
	// Meta parts
	$carz_components = ! empty( $carz_template_args['meta_parts'] )
							? ( is_array( $carz_template_args['meta_parts'] )
								? $carz_template_args['meta_parts']
								: explode( ',', $carz_template_args['meta_parts'] )
								)
							: carz_array_get_keys_by_value( carz_get_theme_option( 'meta_parts' ) );

	// Featured image
	carz_show_post_featured( apply_filters( 'carz_filter_args_featured', 
        array(
			'hover'         => $carz_hover,
			'no_links'      => ! empty( $carz_template_args['no_links'] ),
			'thumb_size'    => ! empty( $carz_template_args['thumb_size'] )
								? $carz_template_args['thumb_size']
								: carz_get_thumb_size(
									carz_is_blog_style_use_masonry( $carz_blog_style[0] )
										? (	strpos( carz_get_theme_option( 'body_style' ), 'full' ) !== false || $carz_columns < 3
											? 'masonry-big'
											: 'masonry'
											)
										: (	strpos( carz_get_theme_option( 'body_style' ), 'full' ) !== false || $carz_columns < 3
											? 'square'
											: 'square'
											)
								),
			'thumb_bg' => carz_is_blog_style_use_masonry( $carz_blog_style[0] ) ? false : true,
			'show_no_image' => true,
			'meta_parts'    => $carz_components,
			'class'         => 'dots' == $carz_hover ? 'hover_with_info' : '',
			'post_info'     => 'dots' == $carz_hover
										? '<div class="post_info"><h5 class="post_title">'
											. ( ! empty( $carz_post_link )
												? '<a href="' . esc_url( $carz_post_link ) . '"' . ( ! empty( $target ) ? $target : '' ) . '>'
												: ''
												)
												. esc_html( get_the_title() ) 
											. ( ! empty( $carz_post_link )
												? '</a>'
												: ''
												)
											. '</h5></div>'
										: '',
            'thumb_ratio'   => 'info' == $carz_hover ?  '100:102' : '',
        ),
        'content-portfolio',
        $carz_template_args
    ) );
	?>
</article></div><?php
// Need opening PHP-tag above, because <article> is a inline-block element (used as column)!