<?php
/**
 * The default template to display the content
 *
 * Used for index/archive/search.
 *
 * @package CARZ
 * @since CARZ 1.0
 */

$carz_template_args = get_query_var( 'carz_template_args' );
$carz_columns = 1;
if ( is_array( $carz_template_args ) ) {
	$carz_columns    = empty( $carz_template_args['columns'] ) ? 1 : max( 1, $carz_template_args['columns'] );
	$carz_blog_style = array( $carz_template_args['type'], $carz_columns );
	if ( ! empty( $carz_template_args['slider'] ) ) {
		?><div class="slider-slide swiper-slide">
		<?php
	} elseif ( $carz_columns > 1 ) {
	    $carz_columns_class = carz_get_column_class( 1, $carz_columns, ! empty( $carz_template_args['columns_tablet']) ? $carz_template_args['columns_tablet'] : '', ! empty($carz_template_args['columns_mobile']) ? $carz_template_args['columns_mobile'] : '' );
		?>
		<div class="<?php echo esc_attr( $carz_columns_class ); ?>">
		<?php
	}
} else {
	$carz_template_args = array();
}
$carz_expanded    = ! carz_sidebar_present() && carz_get_theme_option( 'expand_content' ) == 'expand';
$carz_post_format = get_post_format();
$carz_post_format = empty( $carz_post_format ) ? 'standard' : str_replace( 'post-format-', '', $carz_post_format );
?>
<article id="post-<?php the_ID(); ?>" data-post-id="<?php the_ID(); ?>"
	<?php
	post_class( 'post_item post_item_container post_layout_excerpt post_format_' . esc_attr( $carz_post_format ) );
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
								: array_map( 'trim', explode( ',', $carz_template_args['meta_parts'] ) )
								)
							: carz_array_get_keys_by_value( carz_get_theme_option( 'meta_parts' ) );
	carz_show_post_featured( apply_filters( 'carz_filter_args_featured',
		array(
			'no_links'   => ! empty( $carz_template_args['no_links'] ),
			'hover'      => $carz_hover,
			'meta_parts' => $carz_components,
			'thumb_size' => ! empty( $carz_template_args['thumb_size'] )
							? $carz_template_args['thumb_size']
							: carz_get_thumb_size( strpos( carz_get_theme_option( 'body_style' ), 'full' ) !== false
								? 'full'
								: ( $carz_expanded 
									? 'huge' 
									: 'big' 
									)
								),
		),
		'content-excerpt',
		$carz_template_args
	) );

	// Title and post meta
	$carz_show_title = get_the_title() != '';
	$carz_show_meta  = count( $carz_components ) > 0 && ! in_array( $carz_hover, array( 'border', 'pull', 'slide', 'fade', 'info' ) );

	if ( $carz_show_title ) {
		?>
		<div class="post_header entry-header">
			<?php
			// Post title
			if ( apply_filters( 'carz_filter_show_blog_title', true, 'excerpt' ) ) {
				do_action( 'carz_action_before_post_title' );
				if ( empty( $carz_template_args['no_links'] ) ) {
					the_title( sprintf( '<h3 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' );
				} else {
					the_title( '<h3 class="post_title entry-title">', '</h3>' );
				}
				do_action( 'carz_action_after_post_title' );
			}
			?>
		</div><!-- .post_header -->
		<?php
	}

	// Post content
	if ( apply_filters( 'carz_filter_show_blog_excerpt', empty( $carz_template_args['hide_excerpt'] ) && carz_get_theme_option( 'excerpt_length' ) > 0, 'excerpt' ) ) {
		?>
		<div class="post_content entry-content">
			<?php

			// Post meta
			if ( apply_filters( 'carz_filter_show_blog_meta', $carz_show_meta, $carz_components, 'excerpt' ) ) {
				if ( count( $carz_components ) > 0 ) {
					do_action( 'carz_action_before_post_meta' );
					carz_show_post_meta(
						apply_filters(
							'carz_filter_post_meta_args', array(
								'components' => join( ',', $carz_components ),
								'seo'        => false,
								'echo'       => true,
							), 'excerpt', 1
						)
					);
					do_action( 'carz_action_after_post_meta' );
				}
			}

			if ( carz_get_theme_option( 'blog_content' ) == 'fullpost' ) {
				// Post content area
				?>
				<div class="post_content_inner">
					<?php
					do_action( 'carz_action_before_full_post_content' );
					the_content( '' );
					do_action( 'carz_action_after_full_post_content' );
					?>
				</div>
				<?php
				// Inner pages
				wp_link_pages(
					array(
						'before'      => '<div class="page_links"><span class="page_links_title">' . esc_html__( 'Pages:', 'carz' ) . '</span>',
						'after'       => '</div>',
						'link_before' => '<span>',
						'link_after'  => '</span>',
						'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'carz' ) . ' </span>%',
						'separator'   => '<span class="screen-reader-text">, </span>',
					)
				);
			} else {
				// Post content area
				carz_show_post_content( $carz_template_args, '<div class="post_content_inner">', '</div>' );
			}

			// More button
			if ( apply_filters( 'carz_filter_show_blog_readmore',  ! isset( $carz_template_args['more_button'] ) || ! empty( $carz_template_args['more_button'] ), 'excerpt' ) ) {
				if ( empty( $carz_template_args['no_links'] ) ) {
					do_action( 'carz_action_before_post_readmore' );
					if ( carz_get_theme_option( 'blog_content' ) != 'fullpost' ) {
						carz_show_post_more_link( $carz_template_args, '<p>', '</p>' );
					} else {
						carz_show_post_comments_link( $carz_template_args, '<p>', '</p>' );
					}
					do_action( 'carz_action_after_post_readmore' );
				}
			}

			?>
		</div><!-- .entry-content -->
		<?php
	}
	?>
</article>
<?php

if ( is_array( $carz_template_args ) ) {
	if ( ! empty( $carz_template_args['slider'] ) || $carz_columns > 1 ) {
		?>
		</div>
		<?php
	}
}
