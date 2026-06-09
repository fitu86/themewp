<?php
/**
 * The template 'Style 5' to displaying related posts
 *
 * @package CARZ
 * @since CARZ 1.0.54
 */

$carz_link        = get_permalink();
$carz_post_format = get_post_format();
$carz_post_format = empty( $carz_post_format ) ? 'standard' : str_replace( 'post-format-', '', $carz_post_format );
?><div id="post-<?php the_ID(); ?>" <?php post_class( 'related_item post_format_' . esc_attr( $carz_post_format ) ); ?> data-post-id="<?php the_ID(); ?>">
	<?php
	carz_show_post_featured(
		array(
			'thumb_size'    => apply_filters( 'carz_filter_related_thumb_size', carz_get_thumb_size( (int) carz_get_theme_option( 'related_posts' ) == 1 ? 'big' : 'med' ) ),
		)
	);
	?>
	<div class="post_header entry-header">
		<h6 class="post_title entry-title"><a href="<?php echo esc_url( $carz_link ); ?>"><?php
			if ( '' == get_the_title() ) {
				esc_html_e( '- No title -', 'carz' );
			} else {
				the_title();
			}
		?></a></h6>
		<?php
		if ( in_array( get_post_type(), array( 'post', 'attachment' ) ) ) {
			?>
			<div class="post_meta">
				<a href="<?php echo esc_url( $carz_link ); ?>" class="post_meta_item post_date"><?php echo wp_kses_data( carz_get_date() ); ?></a>
			</div>
			<?php
		}
		?>
	</div>
</div>
