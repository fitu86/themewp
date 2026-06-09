<?php
/**
 * The "Style 5" template to display the content of the single post or attachment:
 * featured image placed to the post header and title placed inside content
 *
 * @package CARZ
 * @since CARZ 1.75.0
 */
?>
<article id="post-<?php the_ID(); ?>"
	<?php
	post_class( 'post_item_single'
		. ' post_type_' . esc_attr( get_post_type() ) 
		. ' post_format_' . esc_attr( str_replace( 'post-format-', '', get_post_format() ) )
	);
	carz_add_seo_itemprops();
	?>
>
<?php

	do_action( 'carz_action_before_post_data' );

	carz_add_seo_snippets();

	// Single post thumbnail and title
	if ( apply_filters( 'carz_filter_single_post_header', is_singular( 'post' ) || is_singular( 'attachment' ) ) ) {
        $carz_post_format = str_replace( 'post-format-', '', get_post_format() );
        $post_meta = in_array( $carz_post_format, array( 'video' ) ) ? get_post_meta( get_the_ID(), 'trx_addons_options', true ) : false;
        $video_autoplay = ! empty( $post_meta['video_autoplay'] )
            && ! empty( $post_meta['video_list'] )
            && is_array( $post_meta['video_list'] )
            && count( $post_meta['video_list'] ) == 1
            && ( ! empty( $post_meta['video_list'][0]['video_url'] ) || ! empty( $post_meta['video_list'][0]['video_embed'] ) );

        // Featured image
		ob_start();
		carz_show_post_featured_image( array(
			'thumb_bg' => false,
			'class'    => 'alignwide',
            'popup'    => false,
            'class_avg' => $video_autoplay
                ? 'with_video with_video_autoplay'   // 'with_thumb' is removed
                : '',
            'autoplay'  => $video_autoplay,
            'post_meta' => $post_meta
		) );
		$carz_post_header = ob_get_contents();
		ob_end_clean();
		$carz_with_featured_image = carz_is_with_featured_image( $carz_post_header );
		if ( strpos( $carz_post_header, 'post_featured' ) !== false
			|| strpos( $carz_post_header, 'post_meta' ) !== false
		) {
			?>
			<div class="post_header_wrap post_header_wrap_in_content post_header_wrap_style_<?php
				echo esc_attr( carz_get_theme_option( 'single_style' ) );
				if ( $carz_with_featured_image ) {
					echo ' with_featured_image';
				}
			?>">
				<?php
				carz_show_layout( $carz_post_header );
				?>
			</div>
			<?php
		}
	}

	do_action( 'carz_action_before_post_content' );

	// Post content
	$carz_meta_components = carz_array_get_keys_by_value( carz_get_theme_option( 'meta_parts' ) );
	$carz_share_position  = carz_array_get_keys_by_value( carz_get_theme_option( 'share_position' ) );
	?>
	<div class="post_content post_content_single entry-content<?php
		if ( in_array( 'left', $carz_share_position ) && in_array( 'share', $carz_meta_components ) ) {
			echo ' post_info_vertical_present' . ( in_array( 'top', $carz_share_position ) ? ' post_info_vertical_hide_on_mobile' : '' );
		}
	?>"<?php
		if ( carz_is_on( carz_get_theme_option( 'seo_snippets' ) ) ) {
			?> itemprop="mainEntityOfPage"<?php
		}
	?>>
		<?php
		if ( in_array( 'left', $carz_share_position ) && in_array( 'share', $carz_meta_components ) ) {
			?><div class="post_info_vertical<?php
				if ( carz_get_theme_option( 'share_fixed' ) > 0 ) {
					echo ' post_info_vertical_fixed';
				}
			?>"><?php
				carz_show_post_meta(
					apply_filters(
						'carz_filter_post_meta_args',
						array(
							'components'      => 'share',
							'class'           => 'post_share_vertical',
							'share_type'      => 'block',
							'share_direction' => 'vertical',
						),
						'single',
						1
					)
				);
			?></div><?php
		}
		the_content();
		?>
	</div>
	<?php
	do_action( 'carz_action_after_post_content' );
	
	// Post footer: Tags, likes, share, author, prev/next links and comments
	do_action( 'carz_action_before_post_footer' );
	?>
	<div class="post_footer post_footer_single entry-footer">
		<?php
		carz_show_post_pagination();
		if ( is_single() && ! is_attachment() ) {
			carz_show_post_footer();
		}
		?>
	</div>
	<?php
	do_action( 'carz_action_after_post_footer' );
	?>
</article>
