<?php
/**
 * The template to display the attachment
 *
 * @package CARZ
 * @since CARZ 1.0
 */


get_header();

while ( have_posts() ) {
	the_post();

	// Display post's content
	get_template_part( apply_filters( 'carz_filter_get_template_part', 'templates/content', 'single-' . carz_get_theme_option( 'single_style' ) ), 'single-' . carz_get_theme_option( 'single_style' ) );

	// Parent post navigation.
	$carz_posts_navigation = carz_get_theme_option( 'posts_navigation' );
	if ( 'links' == $carz_posts_navigation ) {
		?>
		<div class="nav-links-single<?php
			if ( ! carz_is_off( carz_get_theme_option( 'posts_navigation_fixed', 0 ) ) ) {
				echo ' nav-links-fixed fixed';
			}
		?>">
			<?php
			the_post_navigation( apply_filters( 'carz_filter_post_navigation_args', array(
					'prev_text' => '<span class="nav-arrow"></span>'
						. '<span class="meta-nav" aria-hidden="true">' . esc_html__( 'Published in', 'carz' ) . '</span> '
						. '<span class="screen-reader-text">' . esc_html__( 'Previous post:', 'carz' ) . '</span> '
						. '<h5 class="post-title">%title</h5>'
						. '<span class="post_date">%date</span>',
			), 'image' ) );
			?>
		</div>
		<?php
	}

	// Comments
	do_action( 'carz_action_before_comments' );
	comments_template();
	do_action( 'carz_action_after_comments' );
}

get_footer();
