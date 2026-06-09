<?php
/**
 * The custom template to display the content
 *
 * Used for index/archive/search.
 *
 * @package CARZ
 * @since CARZ 1.0.50
 */

$carz_template_args = get_query_var( 'carz_template_args' );
if ( is_array( $carz_template_args ) ) {
	$carz_columns    = empty( $carz_template_args['columns'] ) ? 2 : max( 1, $carz_template_args['columns'] );
	$carz_blog_style = array( $carz_template_args['type'], $carz_columns );
} else {
	$carz_template_args = array();
	$carz_blog_style = explode( '_', carz_get_theme_option( 'blog_style' ) );
	$carz_columns    = empty( $carz_blog_style[1] ) ? 2 : max( 1, $carz_blog_style[1] );
}
$carz_blog_id       = carz_get_custom_blog_id( join( '_', $carz_blog_style ) );
$carz_blog_style[0] = str_replace( 'blog-custom-', '', $carz_blog_style[0] );
$carz_expanded      = ! carz_sidebar_present() && carz_get_theme_option( 'expand_content' ) == 'expand';
$carz_components    = ! empty( $carz_template_args['meta_parts'] )
							? ( is_array( $carz_template_args['meta_parts'] )
								? join( ',', $carz_template_args['meta_parts'] )
								: $carz_template_args['meta_parts']
								)
							: carz_array_get_keys_by_value( carz_get_theme_option( 'meta_parts' ) );
$carz_post_format   = get_post_format();
$carz_post_format   = empty( $carz_post_format ) ? 'standard' : str_replace( 'post-format-', '', $carz_post_format );

$carz_blog_meta     = carz_get_custom_layout_meta( $carz_blog_id );
$carz_custom_style  = ! empty( $carz_blog_meta['scripts_required'] ) ? $carz_blog_meta['scripts_required'] : 'none';

if ( ! empty( $carz_template_args['slider'] ) || $carz_columns > 1 || ! carz_is_off( $carz_custom_style ) ) {
	?><div class="
		<?php
		if ( ! empty( $carz_template_args['slider'] ) ) {
			echo 'slider-slide swiper-slide';
		} else {
			echo esc_attr( ( carz_is_off( $carz_custom_style ) ? 'column' : sprintf( '%1$s_item %1$s_item', $carz_custom_style ) ) . "-1_{$carz_columns}" );
		}
		?>
	">
	<?php
}
?>
<article id="post-<?php the_ID(); ?>" data-post-id="<?php the_ID(); ?>"
	<?php
	post_class(
			'post_item post_item_container post_format_' . esc_attr( $carz_post_format )
					. ' post_layout_custom post_layout_custom_' . esc_attr( $carz_columns )
					. ' post_layout_' . esc_attr( $carz_blog_style[0] )
					. ' post_layout_' . esc_attr( $carz_blog_style[0] ) . '_' . esc_attr( $carz_columns )
					. ( ! carz_is_off( $carz_custom_style )
						? ' post_layout_' . esc_attr( $carz_custom_style )
							. ' post_layout_' . esc_attr( $carz_custom_style ) . '_' . esc_attr( $carz_columns )
						: ''
						)
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
	// Custom layout
	do_action( 'carz_action_show_layout', $carz_blog_id, get_the_ID() );
	?>
</article><?php
if ( ! empty( $carz_template_args['slider'] ) || $carz_columns > 1 || ! carz_is_off( $carz_custom_style ) ) {
	?></div><?php
	// Need opening PHP-tag above just after </div>, because <div> is a inline-block element (used as column)!
}
